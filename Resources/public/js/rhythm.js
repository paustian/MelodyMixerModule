
class RhythmLevel extends AbstractLevel{
    init_game() {
        g_canvas = document.getElementById("melodyMixerArea");
        g_stage = new createjs.Stage(g_canvas);
        g_canvas.width = window.innerWidth;
        g_canvas.height = window.innerHeight;
        createjs.Touch.enable(g_stage);
        // enabled mouse over / out events
        g_stage.enableMouseOver(10);
        g_stage.mouseMoveOutside = true;

        this.queue = new createjs.LoadQueue();
        this.queue.on("complete", this.assets_loaded.bind(this));

        this.queue.loadManifest([
            {id: "bach17", src: "/images/assets/Bach17.png"},
            {id: "brahms17", src: "/images/assets/Brahms17.png"},
            {id: "pauper_stage", src: "/images/assets/Pauper_stage.png"},
            {id: "mozart17", src: "/images/assets/Mozart17.png"},
            {id: "strav17", src: "/images/assets/Strav17.png"},
            {id: "mixer_button", src: "/images/assets/MixerButton.png"},
            {id: "bubble", src: "/images/assets/thoughtBubble.png"},
        ])

    }

    assets_loaded(){
        this.create_screen();
        this.resize();
    }

    navigate_buttons(evt){
        switch (evt.currentTarget.fnc_call){
            case 'main':
                window.open(Routing.generate("paustianmelodymixermodule_navi_main"), "_self");
                break;
        }

    }

    handleEvent(evt) {
        switch (evt.type) {
            case "resize":
                this.resize();
        }

    }
    create_screen(){
        //create the front stage
        this.music_stage = this.place_image_on_screen("pauper_stage", 0, 0);
        g_canvas.width = this.music_stage.getBounds().width;
        g_canvas.height = this.music_stage.getBounds().height;
        //create and place the mixer buttons
        let x = 50;
        this.training_level_button = this.place_image_on_screen("mixer_button", x, 50, this.navigate_buttons.bind(this), "Main Navigation");
        this.training_level_button.fnc_call = "main";
        this.mozart17 = this.place_image_on_screen("mozart17", 0, 0);
        this.brahms17 = this.place_image_on_screen("brahms17", 0, 0);
        this.strav17 = this.place_image_on_screen("strav17", 0, 0);
        this.bach17 = this.place_image_on_screen("bach17", 0, 0);
    }

    calculate_positions(){
        this.bach17.x = this.strav17.x + this.strav17.getBounds().width * this.strav17.scaleX;
        this.brahms17.x = this.bach17.x +  this.bach17.getBounds().width * this.bach17.scaleX;
        this.mozart17.x = this.brahms17.x + this.brahms17.getBounds().width * this.brahms17.scaleX;

        let h = this.music_stage.getBounds().height * this.music_stage.scaleY;
        this.strav17.y = h - this.strav17.getBounds().height * this.strav17.scaleY - 10;
        this.bach17.y = h - this.bach17.getBounds().height * this.bach17.scaleY - 10;
        this.brahms17.y = h - this.brahms17.getBounds().height * this.brahms17.scaleY - 10;
        this.mozart17.y = h - this.mozart17.getBounds().height * this.mozart17.scaleY - 10;
    }

    resize(){
        this.calculate_widths(this.strav17, 4);
        this.calculate_widths(this.bach17);
        this.calculate_widths(this.brahms17, 4);
        this.calculate_widths(this.mozart17);
        this.calculate_widths(this.music_stage, 1);
        this.calculate_positions();
        g_stage.update();
    }
}

function initLevel (){
    let level = new RhythmLevel();
    level.init_game();
    window.addEventListener('resize', level, false);
}