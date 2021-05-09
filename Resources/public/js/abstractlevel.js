let g_canvas = '';
let g_stage = '';
let g_dir = "";
let g_font = "16px Arial"
let g_color = "#FFFFFF";
let gLevelId = 0;
let gExNum = 0;
let gMaxWidth = 1300;

class AbstractLevel {
    
    constructor(){
        //this.wipe_screen();
        g_canvas = document.getElementById("melodyMixerArea");
        g_stage = new createjs.Stage(g_canvas);
        g_canvas.width = window.innerWidth;
        g_canvas.height = window.innerHeight;
        this.duration = 0;
        createjs.Touch.enable(g_stage);
        // enabled mouse over / out events
        g_stage.enableMouseOver(10);
        g_stage.mouseMoveOutside = true;
    }

    init_game(){
        this.queue = new createjs.LoadQueue();
        //install the sound plug in before loading.
        this.queue.installPlugin(createjs.Sound);
        this.queue.on("complete", this.assets_loaded.bind(this));
        this.send_ajax(
            "paustianmelodymixermodule_ajax_getleveldata",
            {"levelNum": gLevelId, "exNum": gExNum},
            "POST",
            this.get_level_data.bind(this));
    }

    get_level_data(result, textStatus, jqXHR){
        //this is used to build the manifest
        this.graphicData = result.graphicData;
        //This we use later to create the score matrix
        this.scoreData = result.scoreData;
        let manSize = this.graphicData.length;
        let manifest = [];
        //an array to keep track of dupicate requests
        let loadedItems = [];
        let item = "";

        //build the manifest. Note g_dir is the path to the assests folder that has all the files.
        for(let i = 0; i < manSize; i++){
            item = this.graphicData[i];
            let jsonObj = {};
            //some items will use the same graphic but have different text or location
            //only load it once.
            if(loadedItems.includes(item['gsName'])){
                continue;
            }
            loadedItems.push(item['gsName']);
            jsonObj.id = item['gsName'];
            jsonObj.src = g_dir + item['gsPath'];
            manifest.push(jsonObj);
        }
        //We will probably need the thought bubble on every stage, so just encode it here.
        let thoughtBubble = {};
        thoughtBubble.id = "bubble";
        thoughtBubble.src = "/images/assets/thoughtBubble.png";
        manifest.push(thoughtBubble);
        this.queue.loadManifest(manifest);
    }

    wipe_screen(){
        g_stage.removeAllChildren();
        g_stage.update();
    }

    assets_loaded(){
        this.create_screen();
        this.create_music_interface();
        this.resize();
    }
    create_screen(){
        let numGraphics = this.graphicData.length;
        let item = [];

        for(let i = 0; i < numGraphics; i++){
            item = this.graphicData[i];
            //if its a graphic associated with the sound array we don't want to put it on the screen now
            if(this._skip_item(item['gsName'])){
                continue;
            }
            let ext = item['gsPath'].split('.').pop();
            //If it's a midi file (ext 'mid') it's not a graphic and should not
            //be put on the screen
            if(ext !== 'mid') {
                let gName = item['gsName']
                if(gName.includes("stage")){
                    this.place_image_on_screen(item['gsName'],
                        item['xPos'],
                        item['yPos'],
                        "",
                        item['gsLabel'],
                        item['descText'],
                        item['boxWidth'],
                        item['xDes'],
                        item['yDes'],
                        g_font,
                        item['gsUrl']);
                } else {
                    let container = this.place_image_on_screen(item['gsName'],
                        item['xPos'],
                        item['yPos'],
                        this.navigate_buttons.bind(this),
                        item['gsLabel'],
                        item['descText'],
                        item['boxWidth'],
                        item['xDes'],
                        item['yDes'],
                        g_font,
                        item['gsUrl']);
                    if(gName.includes("clef")) {
                        this.create_background_box(container, 0);
                    }
                }
            }
        }
    }

    _skip_item(name){
        //is it in the sound array?
        for (let i = 0; i < this.scoreData.length; i++) {
            let item = this.scoreData[i];
            if(item['gsGraphic'] === name){
                return true;
            }
        }
        return false;
    }

    navigate_buttons(evt){
    }

    create_music_interface(){
        this.assign_boxes(this.scoreData);
        this.init_music_player();
    }

    init_music_player(){
        this.soundArray = [];
        createjs.Ticker.on("tick", this.tick.bind(this));
        createjs.Ticker.paused = true;

        //Grab the controls for updates
        //tempo slider
        this.tempo_slider = document.getElementById("tempo_slider");
        //The function that gets called when the slider is moved
        this.tempo_slider.oninput = this.tempo_slider_input.bind(this);
        //A display of the current tempo
        this.output = document.getElementById("tempoSpan");
        //set the inital value
        this.output.innerText = this.tempo_slider.value;
        //A slider to control where in the sound file you are
        this.duration_slider = document.getElementById("duration_slider");
        this.duration_slider.oninput = this.duration_slider_input.bind(this);
       
        this.playButton = document.getElementById("play");
        this.playButton.onclick = this.play_music.bind(this);
        // Initialize player and register event handler
        this.player = new g_midiPlayer.Player(this.play_midi.bind(this));
        this.player.on("playing", this._music_is_playing.bind(this));
        this.player.on("endOfFile", this._music_done.bind(this));
       
        this.totalTicks = 0;
        //now we need a sound font
        this.ac = new AudioContext || new webkitAudioContext;
        this.instrument = null;
        Soundfont.instrument(this.ac, 'acoustic_grand_piano').then(this.load_instrument.bind(this));
        
        let scoreButton = document.getElementById("score");
        scoreButton.onclick = this.calculate_score.bind(this);
        this.scoreText = document.getElementById("scoretext");
        this.instrument_menu = document.getElementById("instrumentMenu");
        let instrument_choices = ["acoustic_grand_piano",
        "bright_acoustic_piano",
        "electric_grand_piano",
        "honkytonk_piano",
        "electric_piano_1",
        "electric_piano_2",
        "harpsichord",
        "clavinet",
        "celesta",
        "glockenspiel",
        "music_box",
        "vibraphone",
        "marimba",
        "xylophone",
        "dulcimer",
        "drawbar_organ",
        "percussive_organ",
        "rock_organ",
        "church_organ",
        "reed_organ",
        "accordion",
        "harmonica",
        "tango_accordion",
        "acoustic_guitar_nylon",
        "acoustic_guitar_steel",
        "electric_guitar_clean",
        "acoustic_bass",
        "electric_bass_finger",
        "electric_bass_pick",
        "fretless_bass",
        "slap_bass_1",
        "synth_bass_1",
        "violin",
        "viola",
        "cello",
        "contrabass",
        "tremolo_strings",
        "pizzicato_strings",
        "orchestral_harp",
        "string_ensemble_1",
        "synth_strings_1",
        "voice_oohs",
        "orchestra_hit",
        "trumpet",
        "trombone",
        "tuba",
        "muted_trumpet",
        "french_horn",
        "brass_section",
        "synth_brass_1",
        "soprano_sax",
        "alto_sax",
        "tenor_sax",
        "baritone_sax",
        "oboe",
        "english_horn",
        "bassoon",
        "clarinet",
        "piccolo",
        "flute",
        "recorder",
        "pan_flute",
        "blown_bottle",
        "shakuhachi",
        "whistle",
        "ocarina",
        "sitar",
        "banjo",
        "shamisen",
        "koto",
        "kalimba",
        "bagpipe",
        "fiddle",
        "shanai",
        "tinkle_bell",
        "steel_drums",
        "woodblock",
        "bird_tweet",
        "telephone_ring",
        "helicopter",
        "applause"];
        for(let j = 0; j < instrument_choices.length; j++){
            var opt = instrument_choices[j];
            var el = document.createElement("option");
            el.textContent = opt;
            el.value = opt;
            this.instrument_menu.appendChild(el);
        }
        this.instrument_menu.onchange = this.set_instrument.bind(this);
    }
    
    tempo_slider_input(){
        let playing = this.player.isPlaying();
        if(playing){
            this.player.pause();
        }
        let tempo = this.tempo_slider.value;
        this.output.innerText = tempo;
        this.player.setTempo(tempo);
        if(playing){
            this.player.play();
        }
    }

    duration_slider_input(){
        if(this.totalTicks === 0){
            return;
        }
        //you need to calculate the the percentage of ticks by the value.
        this.player.skipToPercent(this.duration_slider.value);
    }
    
    calculate_score(){
        let totalScore = 0;
        for (let i = 1; i < 5; i++){
            let musicBox = eval("this.box" + i);
            if(musicBox.occupant !== null){
                if(musicBox.occupant.score > 0){
                    //20 points for having the right item
                    totalScore += 20;
                }
                if(musicBox.occupant.order === i){
                    //5 points for having it in the right order
                    totalScore += 5;
                }
            }
        }
        this.scoreText.value = totalScore;
        this._post_score_to_database(totalScore);
    }
    
    set_instrument(){
        let instrument = this.instrument_menu.value;
        Soundfont.instrument(this.ac, instrument).then(this.load_instrument.bind(this));
    }

    _shuffle_array(arr) {
        for (let i = arr.length - 1; i > 0; i--) {
          const j = Math.floor(Math.random() * (i + 1));
          [arr[i], arr[j]] = [arr[j], arr[i]];
        }
    }

    assign_boxes(musicArray){
        this.boxes = [];

        this._shuffle_array(musicArray);
        //create the 8 squares for the images to reside in
        //Use a class array to store the boxes then you can iterate
        let boxGraphic = null;
        let item = null;
        let boxBkgnd = null;
        //ToDo: Move the boxes farther down, instead of 50 and 150.
        for(let i=0; i < 8; i++){
            item = musicArray[i];
            if (i < 4){
                boxGraphic = this.place_image_on_screen(item['gsGraphic'], 500 + (i%4)*200, 150);
            } else {
                boxGraphic = this.place_image_on_screen(item['gsGraphic'], 500 + (i%4)*200, 250);
            }
            boxBkgnd = this.set_up_drop_container(boxGraphic, item['gsMidi'], true, item['scoreIt'], item['musicOrder']);
            this.boxes.push(boxBkgnd);
        }
        //Place the arrangement boxes on screen
        let width = boxBkgnd.width;
        let height = boxBkgnd.height;
        this.box1 = this.create_box(540, 350, width, height);
        this.box2 = this.create_box(this.box1.x + width, 350, width, height);
        this.box3 = this.create_box(this.box2.x + width, 350, width, height);
        this.box4 = this.create_box(this.box3.x + width, 350, width, height);
    }
    
    place_image_on_screen(image_id, x, y, listen_function = "", text = "", textbox="", textbox_width=0, tx_x=0, tx_y=0, inFont = "16px Times black", url=""){
        let new_image = new createjs.Bitmap(this.queue.getResult(image_id));
        let image_container = new createjs.Container();
        if(listen_function != ""){
            image_container.addEventListener("click", listen_function);
            image_container.url =url;
        }
        //we want text underneath the image
        if (text != ""){
            let labelText = new createjs.Text(text, g_font, g_color);
            labelText.x = 0;
            labelText.y = new_image.image.height + 2;
            image_container.addChild(labelText);
        }
        
        if(textbox != ""){
            image_container.textbox = image_container.addChild(this.create_textbox(tx_x, tx_y, textbox_width, textbox, inFont));
        }
        
        image_container.width = new_image.image.width;
        image_container.height = new_image.image.height;
        image_container.x = x;
        image_container.y = y;
        image_container.addChildAt(new_image, 0);
        if(image_id.includes("stage")){
            g_stage.addChildAt(image_container, 0);
        } else {
            g_stage.addChild(image_container);
        }
        return image_container;
    }



    calculate_widths(object, divisor = 4, adjustment = 1){
        let old_width = object.getBounds().width;
        object.scaleX = (g_canvas.width/divisor)/old_width * adjustment;
        object.scaleY = object.scaleX;
    }

    create_box(x, y, width, height){
        let theContainer = new createjs.Container();
        theContainer.x = x;
        theContainer.y = y;
        theContainer.width = width;
        theContainer.height = height;
        //Add a background rectangle
        let box = new createjs.Shape();
        box.graphics.setStrokeStyle(3).beginStroke("#000000");
		box.graphics.beginFill("white").drawRect(0, 0, width, height);
        theContainer.addChild(box);
        g_stage.addChildAt(theContainer, 1);
        //all drop targets begin empty.
        theContainer.occupant = null;
        return theContainer;
    }

    create_textbox(x, y, width, text, inFont = "16px Times black"){
        var image = this.queue.getResult("bubble");
        var text_box = new createjs.ScaleBitmap(image, new createjs.Rectangle(12, 12, 5, 10));
        let label_text = new createjs.Text().set({
        text: text,
        x: 5,
        y: 7,
        font: inFont,
        lineWidth: width - 10,
        });
        //The extra 20 is to take account of the little pointer for the text box
        text_box.setDrawSize(width, label_text.getBounds().height + 30);
        let text_container = new createjs.Container();
        text_container.setBounds(x, y, text_box.width, text_box.height)
        text_container.addChild(text_box, label_text);
        text_container.x = x;
        text_container.y = -y;
        return text_container;
    }

    set_up_drop_container(imageContainer, soundToPlay, addListeners = true, score = 0, order = 0){
        if(addListeners){
            imageContainer.on("mousedown", this.drag_me_interface.bind(this));
            imageContainer.on("pressup", this.drop_on_me_target.bind(this));
            imageContainer.on("pressmove", this.move_box.bind(this));
        }
        let dropArea = new createjs.Shape();
        dropArea.graphics.setStrokeStyle(3).beginStroke("#000000");
	    dropArea.graphics.beginFill("white").drawRect(0, 0, imageContainer.width, imageContainer.height);

        //Attach the sounds to each image
        imageContainer.soundId = soundToPlay;
        //set up the scoring parameters
        imageContainer.score = score;
        imageContainer.order = order;
        imageContainer.addChildAt(dropArea, 0);

        return this.create_background_box(imageContainer);

    }

    create_background_box(imageContainer, where = 1){
        let backgroundBox = new createjs.Shape();
        backgroundBox.graphics.setStrokeStyle(3).beginStroke("#000000");
		backgroundBox.graphics.beginFill("white").drawRect(0, 0, imageContainer.width, imageContainer.height);
        backgroundBox.x = imageContainer.x;
        backgroundBox.y = imageContainer.y;
        backgroundBox.width = imageContainer.width;
        backgroundBox.height = imageContainer.height;
        //all drop cotainers being being occupied.
        backgroundBox.occupant = imageContainer;
        g_stage.addChildAt(backgroundBox, where);
        return backgroundBox;
    }

    load_instrument(instrument){
        this.instrument = instrument;
    }

    play_midi(e){
        if(this.instrument === null){
            return;
        }
        if(e.name === "Note on"){
            this.instrument.play(e.noteName, this.ac.currentTime, {gain:e.velocity/100});
        }
    }

    calculate_positions(){

    }

    resize(){
    }

    drag_me_interface(e){
        //find the box that the music is being dragged out of and set it to empty
        this.dragSource = this._find_box(e.stageX, e.stageY);
        if(!this.dragSource){
            return;
        }
        let dragOccupant = null;
        if(this.dragSource !== false){
            dragOccupant = this.dragSource.occupant;
            this.dragSource.occupant = null;
        }
        //put this object at the top of the stack so it doesn't go behind anything
        g_stage.setChildIndex(dragOccupant, g_stage.numChildren - 1);
        //start keeping track of updates
        createjs.Ticker.paused = false;
    }

    move_box(e){
        e.currentTarget.x = e.stageX - 75;
        e.currentTarget.y = e.stageY - 25;
        this.update = true;
    }

    _is_in_box(x, y, box){
        if((x > box.x) && (x < (box.x + box.width)) &&
            (y > box.y) && (y < (box.y + box.height))){
                return true;
        }
        return false;
    }
 
    _move_to_target(draggedBox, box){
        draggedBox.x = box.x;
        draggedBox.y = box.y;
        
        if(box.occupant !== null){
            //if there was already an object there, move it to the source.
            box.occupant.x = this.dragSource.x;
            box.occupant.y = this.dragSource.y;
            //set the occupant of the dragSource
            this.dragSource.occupant = box.occupant;

        }
        box.occupant = draggedBox;
    }

    _find_box(x, y){
        for(let i = 0; i < 8; i++){
            if(this._is_in_box(x, y, this.boxes[i])){
                return this.boxes[i];
            }
        }
        if(this._is_in_box(x, y, this.box1)){
            return this.box1;
        } else if (this._is_in_box(x, y, this.box2)){
            return this.box2;
        } else if (this._is_in_box(x, y, this.box3)){
            return this.box3;    
        } else if (this._is_in_box(x, y, this.box4)){
            return this.box4;
        } else {
            return false;
        }
    }
    
    drop_on_me_target(e){
        let targetBox = this._find_box(e.stageX, e.stageY);
        if(!targetBox){
            e.currentTarget.x = this.lastDragSourceX;
            e.currentTarget.y = this.lastDragSourceY;
        } else {
            this._move_to_target(e.currentTarget, targetBox);
        }
        g_stage.update();
        //stop keeping track of updates.
        createjs.Ticker.paused = true;
        //empty the array
        this.soundArray.splice(0, this.soundArray.length)
        
    }

    tick(){
        if(this.update){
            g_stage.update();
            this.update = false;
        }
    }
    
    _create_music_to_play(){
        for (let i = 1; i < 5; i++){
            let musicBox = eval("this.box" + i);
            if(musicBox.occupant !== null){
                let sound = this.queue.getResult(musicBox.occupant.soundId);
                this.soundArray.push(sound);
            }
        }
        let time = 0;
        if(this.soundArray.length != 0){
            let sound = this.soundArray.shift();
            let midiToPlay = new Midi(sound);
            let track = midiToPlay.tracks[0];
            let note = null;
            for(let t = 0; t < track.notes.length; t++){
                time += track.notes[t].durationTicks + 1;
            }
            while(this.soundArray.length != 0){
                sound = this.soundArray.shift();
                const midiPiece = new Midi(sound);
                //there is only one track per midi file
                let midiPieceNotes = midiPiece.tracks[0].notes;
                for(let h = 0; h < midiPieceNotes.length; h++){
                    note = midiPieceNotes[h];
                    note.ticks = time;
                    time += note.durationTicks + 1;
                    track.notes.push(note);
                }
            }
            this.totalTicks = time;
            //this._set_min_seconds(this.totalTicks/1000, this.duration);
            let buffer = new Uint8Array(midiToPlay.toArray());
            this.player.loadArrayBuffer(buffer);
        }
        return time;
    }

    play_music(){
        if(this.player.isPlaying()){
            //set the icon back to play
            this.playButton.classList.remove("bi-pause-btn");
            this.playButton.classList.add("bi-play-btn");
            this.player.pause();
        } else {
            //set the icon to pause
            this.playButton.classList.remove("bi-play-btn");
            this.playButton.classList.add("bi-pause-btn");
            this._create_music_to_play();
            let ticks = this.totalTicks*this.duration_slider.value/100;
            this.player.skipToTick(ticks);
            this.player.play();
            //this is a hack to get around the midiPlayer always setting the tempo to 
            //120 to start.
            setTimeout(this._set_tempo_hack.bind(this), 100, this.tempo_slider.value);
            ;
        }
        
    }
    _set_tempo_hack(tempo){
        this.player.setTempo(tempo);
    }

     _music_done(){
         setTimeout(this._reset_duration_slider.bind(this), 2000);
     }
    _reset_duration_slider(){
        this.duration_slider.value = 0;
        this.playButton.classList.remove("bi-pause-btn");
        this.playButton.classList.add("bi-play-btn");
    }

    _music_is_playing(currentTick){
        //this._set_min_seconds(currentTick.tick/1000, this.current_time, "");
        let currentBarValue = currentTick.tick/this.totalTicks * 100;
        this.duration_slider.value = currentBarValue;
    }

    _post_score_to_database(score){
        //This score should communicate with a module in Zikula and post the score to the database
        //It will have to ID the example and then post the score.
        //The pages that list the table of scores will have to be able to read from the database also.
        //This will have to be hosted on Jamie's Book Site.
        //Actually make these pages part of the module. I could then enter the data in a table and have it all saved there?
    }

    send_ajax(url, data, method, retFunc) {
        var theRoute = Routing.generate(url);
        jQuery.ajax(theRoute,{
            data: data,
            method: method,
            success: retFunc,
            dataType: 'json',
            error: this.ajax_error.bind(this),
            timeout: 100000,
            cache: false});
    }

    ajax_error(jqXHR, textStatus, errorThrown) {
        window.alert(textStatus + "\n" + errorThrown);
    }
}