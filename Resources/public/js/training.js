
class TrainingLevel extends AbstractLevel{
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
            {id: "bach_score", src: "/images/assets/Bach_score.png"},
            {id: "brahms_score", src: "/images/assets/Brahms_score.png"},
            {id: "football_stage", src: "/images/assets/Football.png"},
            {id: "mozart_score", src: "/images/assets/Mozartscore.png"},
            {id: "strav_score", src: "/images/assets/Strav_score.png"},
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
        this.music_stage = this.place_image_on_screen("football_stage", 0, 0);
        g_canvas.width = this.music_stage.getBounds().width;
        g_canvas.height = this.music_stage.getBounds().height;
        //create and place the mixer buttons
        let x = 50;
        this.training_level_button = this.place_image_on_screen("mixer_button", x, 50, this.navigate_buttons.bind(this), "Main Navigation");
        this.training_level_button.fnc_call = "main";
        this.mozart_20 = this.place_image_on_screen("mozart_score", 0, 0);
        this.brahms_20 = this.place_image_on_screen("brahms_score", 0, 0);
        this.strav_20 = this.place_image_on_screen("strav_score", 0, 0);
        this.bach_20 = this.place_image_on_screen("bach_score", 0, 0);
    }

    calculate_positions(){
        this.bach_20.x = this.strav_20.x + this.strav_20.getBounds().width * this.strav_20.scaleX;
        this.brahms_20.x = this.bach_20.x +  this.bach_20.getBounds().width * this.bach_20.scaleX;
        this.mozart_20.x = this.brahms_20.x + this.brahms_20.getBounds().width * this.brahms_20.scaleX;

        let h = this.music_stage.getBounds().height * this.music_stage.scaleY;
        this.strav_20.y = h - this.strav_20.getBounds().height * this.strav_20.scaleY - 10;
        this.bach_20.y = h - this.bach_20.getBounds().height * this.bach_20.scaleY - 10;
        this.brahms_20.y = h - this.brahms_20.getBounds().height * this.brahms_20.scaleY - 10;
        this.mozart_20.y = h - this.mozart_20.getBounds().height * this.mozart_20.scaleY - 10;
    }

    resize(){
        this.calculate_widths(this.strav_20, 4);
        this.calculate_widths(this.bach_20);
        this.calculate_widths(this.brahms_20, 4);
        this.calculate_widths(this.mozart_20);
        this.calculate_widths(this.music_stage, 1);
        this.calculate_positions();
        g_stage.update();
    }
}

function initLevel (){
    let level = new TrainingLevel();
    level.init_game();
    window.addEventListener('resize', level, false);
}