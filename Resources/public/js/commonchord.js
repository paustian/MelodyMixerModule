
class CommonChordLevel extends AbstractLevel{
    init_game() {
        g_color = "#FFFFFF";
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
            {id: "bach20", src: "/images/assets/Bach20.png"},
            {id: "brahms20", src: "/images/assets/Brahms20.png"},
            {id: "rock_stage", src: "/images/assets/Rockstage.png"},
            {id: "mozart20", src: "/images/assets/Mozart20.png"},
            {id: "strav20", src: "/images/assets/Strav20.png"},
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
        this.music_stage = this.place_image_on_screen("rock_stage", 0, 0);
        g_canvas.width = this.music_stage.getBounds().width;
        g_canvas.height = this.music_stage.getBounds().height;
        //create and place the mixer buttons
        let x = 120;
        this.training_level_button = this.place_image_on_screen("mixer_button", x, 150, this.navigate_buttons.bind(this), "Main Navigation");
        this.training_level_button.fnc_call = "main";
        this.mozart20 = this.place_image_on_screen("mozart20", 0, 0);
        this.brahms20 = this.place_image_on_screen("brahms20", 0, 0);
        this.strav20 = this.place_image_on_screen("strav20", 0, 0);
        this.bach20 = this.place_image_on_screen("bach20", 0, 0);
    }

    calculate_positions(){
        this.bach20.x = this.strav20.x + this.strav20.getBounds().width * this.strav20.scaleX - 95;
        this.brahms20.x = this.bach20.x +  this.bach20.getBounds().width * this.bach20.scaleX - 30;
        this.mozart20.x = this.brahms20.x + this.brahms20.getBounds().width * this.brahms20.scaleX - 30;

        let h = this.music_stage.getBounds().height * this.music_stage.scaleY;
        this.strav20.y = h - this.strav20.getBounds().height * this.strav20.scaleY - 50;
        this.bach20.y = h - this.bach20.getBounds().height * this.bach20.scaleY - 10;
        this.brahms20.y = h - this.brahms20.getBounds().height * this.brahms20.scaleY - 10;
        this.mozart20.y = h - this.mozart20.getBounds().height * this.mozart20.scaleY - 10;
    }

    resize(){
        this.calculate_widths(this.music_stage, 1);
        this.calculate_positions();
        g_stage.update();
    }
}

function initLevel (){
    let level = new CommonChordLevel();
    level.init_game();
    window.addEventListener('resize', level, false);
}