
class MainLevel extends AbstractLevel {
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
            {id: "bach_20", src: "/images/assets/Bach20.png"},
            {id: "brahms_20", src: "/images/assets/Brahms20.png"},
            {id: "mozart_20", src: "/images/assets/Mozart20.png"},
            {id: "strav_20", src: "/images/assets/Strav20.png"},
            {id: "rock_stage", src: "/images/assets/Rockstage.png"},
            {id: "bubble", src: "/images/assets/thoughtBubble.png"},
        ]);

    }

    assets_loaded(){
        this.create_screen();
        this.resize();
    }

    navigate_buttons(evt){
        switch (evt.currentTarget.fnc_call){
            case "training":
                window.open(Routing.generate("paustianmelodymixermodule_navi_level", {name: 'training'}), '_self');
                break;
            case "basics":
                window.open(Routing.generate("paustianmelodymixermodule_navi_level", {name: 'basics'}), '_self');;
                break;
            case "rhythm":
                break;
            case 'main':
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
        this.music_stage = this.place_image_on_screen("rock_stage", 0, 0);
        this.music_stage.alpha = 1.0;
        g_canvas.width = this.music_stage.getBounds().width;
        g_canvas.height = this.music_stage.getBounds().height;

        //create and place the mixer buttons
        let x = 50;

        this.mozart_20 = this.place_image_on_screen("mozart_20", 0, 0, this.mozart_clicked.bind(this));
        this.mozart_text = this.create_textbox(this.mozart_20.x, this.mozart_20.y, 350, "I'm Wolfgang Amadeus Mozart from the Classical period." +
            "Yeah I know, Wolfgang's a great name. Hey it's a lot better than Chrysostomus Wolfgangus Theophilus, which is how I started out. " +
            "Just like Bach, I had trouble staying employed, and in fact I was once kicked out, literally in the seat of the pants, from the Archbishops house.");
        this.mozart_text.visible = false;
        this.brahms_20 = this.place_image_on_screen("brahms_20", 0, 0, this.brahms_clicked.bind(this));
        this.brahms_text =  this.create_textbox(this.brahms_20.x, this.brahms_20.y-500, 350, "I'm Johannes Brahms, Romantic composer. People considered me to be " +
            "something of a slob. Maybe it had something to do with my bushy beard and baggy clothing. Sometimes I would forget to attach suspenders, and I'd have to keep grabbing" +
            " my pants to keep them from falling down during rehearsals. Don't want to have that happen in front of a full orhcesra!");
        this.strav_20 = this.place_image_on_screen("strav_20", 0, 0, this.strav_clicked.bind(this));
        this.strav_text =  this.create_textbox(this.strav_20.x, this.strav_20.y, 200, "I'm Igor Stravinsky representing the 20th century. My music caused a riot! " +
            "I guess those primative rhythyms really got to everyone. The entire orchestra was drowned out! Most people got use to my music eventually, but ther was " +
            "that $100 fine for writing an arrangement of the Star Spangled Banner after I moved to America.");
        this.bach_20 = this.place_image_on_screen("bach_20", 0, 0, this.bach_clicked.bind(this), "",
            "Welcome to Melody Mixer.\nClick on one of the buttons to start composing melodies and help us work our way back through time to the Rock and Roll Hall of Fame.",
            300, -60, 100, "20px Times black");
        this.bach_text = this.create_textbox(this.brahms_20.x, this.brahms_20.y, 350, "I'm Johann Sebastian Bach, and I was a composer during the Baroque period. I once " +
            "spent a month in jail because of my employer. He figured it was a great way to keep me from going to work for someone else. While I was in the clink" +
            " I wrote 46 pieces. Hey, I didn't have anything else to do, right? I'm also the one that had a bunch of kids.");
    }

    calculate_positions(){
        this.bach_20.x = this.strav_20.x + this.strav_20.getBounds().width * this.strav_20.scaleX - 60;
        this.brahms_20.x = this.bach_20.x +  this.bach_20.getBounds().width * this.bach_20.scaleX - 150;
        this.mozart_20.x = this.brahms_20.x + this.brahms_20.getBounds().width * this.brahms_20.scaleX - 30;

        let h = this.music_stage.getBounds().height * this.music_stage.scaleY;
        this.strav_20.y = h - this.strav_20.getBounds().height * this.strav_20.scaleY - 10;
        this.bach_20.y = h - this.bach_20.getBounds().height * this.bach_20.scaleY - 10;
        this.brahms_20.y = h - this.brahms_20.getBounds().height * this.brahms_20.scaleY - 10;
        this.mozart_20.y = h - this.mozart_20.getBounds().height * this.mozart_20.scaleY - 10;
        //This is not working. the buttons should move too.
        //this.training_level_button.x = this.training_level_button.x * this.music_stage.scaleX;
    }

    mozart_clicked(){
        if(this.mozart_text.visible == false){
            this.hide_text_bubbles();
            //display the text
            this.mozart_text.x = this.mozart_20.x - 210;
            this.mozart_text.y = this.mozart_20.y - 100;
            this.mozart_text.visible = true;
            g_stage.addChild(this.mozart_text);
            g_stage.update();
        } else {
            this.mozart_text.visible = false;
            g_stage.removeChild(this.mozart_text);
            g_stage.update();
        }
    }

    brahms_clicked(){
        if(this.brahms_text.visible == false){
            this.hide_text_bubbles();
            //display the text
            this.brahms_text.x = this.brahms_20.x - 190;
            this.brahms_text.y = this.brahms_20.y - 150;
            this.brahms_text.visible = true;
            g_stage.addChild(this.brahms_text);
            g_stage.update();
        } else {
            this.brahms_text.visible = false;
            g_stage.removeChild(this.brahms_text);
            g_stage.update();
        }
    }
    bach_clicked(){
        if(this.bach_text.visible == false){
            this.hide_text_bubbles();
            //display the text
            this.bach_text.x = this.bach_20.x - 190;
            this.bach_text.y = this.bach_20.y - 100;
            this.bach_text.visible = true;
            g_stage.addChild(this.bach_text);
            g_stage.update();
        } else {
            this.bach_text.visible = false;
            g_stage.removeChild(this.bach_text);
            g_stage.update();
        }
    }
    strav_clicked(){
        if(this.strav_text.visible == false){
            this.hide_text_bubbles();
            //display the text
            this.strav_text.x = this.strav_20.x;
            this.strav_text.y = this.strav_20.y - 220;
            this.strav_text.visible = true;
            g_stage.addChild(this.strav_text);
            g_stage.update();
        } else {
            this.strav_text.visible = false;
            g_stage.removeChild(this.strav_text);
            g_stage.update();
        }
    }

    hide_text_bubbles(){
        this.mozart_text.visible = false;
        this.brahms_text.visible = false;
        this.bach_text.visible = false;
        this.strav_text.visible = false;
    }
    resize(){
        this.hide_text_bubbles();

        this.calculate_positions();
        g_stage.update();
    }
}

function initMain (){
    let mixer = new MainLevel();
    mixer.init_game();
    window.addEventListener('resize', mixer, false);
}
