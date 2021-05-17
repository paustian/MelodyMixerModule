
class IntervalsLevel extends AbstractLevel{
    init_game() {
        g_canvas = document.getElementById("melodyMixerArea");
        g_stage = new createjs.Stage(g_canvas);
        g_canvas.width = window.innerWidth;
        g_canvas.height = window.innerHeight;
        createjs.Touch.enable(g_stage);
        // enabled mouse over / out events
        g_stage.enableMouseOver(10);
        g_stage.mouseMoveOutside = true;
        g_color = "#000000";
        this.queue = new createjs.LoadQueue();
        this.queue.on("complete", this.assets_loaded.bind(this));

        this.queue.loadManifest([
            {id: "bach18", src: "/images/assets/Bach18.png"},
            {id: "brahms18", src: "/images/assets/Brahms18.png"},
            {id: "baroque_stage", src: "/images/assets/baroque_stage.png"},
            {id: "mozart18", src: "/images/assets/Mozart18.png"},
            {id: "strav18", src: "/images/assets/Strav18.png"},
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
        this.music_stage = this.place_image_on_screen("baroque_stage", 0, 0);
        g_canvas.width = this.music_stage.getBounds().width;
        g_canvas.height = this.music_stage.getBounds().height;
        //create and place the mixer buttons
        let x = 120;
        this.training_level_button = this.place_image_on_screen("mixer_button", x, 150, this.navigate_buttons.bind(this), "Main Navigation");
        this.training_level_button.fnc_call = "main";
        this.mozart18 = this.place_image_on_screen("mozart18", 0, 0);
        this.brahms18 = this.place_image_on_screen("brahms18", 0, 0);
        this.strav18 = this.place_image_on_screen("strav18", 0, 0);
        this.bach18 = this.place_image_on_screen("bach18", 0, 0);
    }

    calculate_positions(){
        this.bach18.x = this.strav18.x + this.strav18.getBounds().width * this.strav18.scaleX - 65;
        this.brahms18.x = this.bach18.x +  this.bach18.getBounds().width * this.bach18.scaleX - 65;
        this.mozart18.x = this.brahms18.x + this.brahms18.getBounds().width * this.brahms18.scaleX - 65;

        let h = this.music_stage.getBounds().height * this.music_stage.scaleY;
        this.strav18.y = h - this.strav18.getBounds().height * this.strav18.scaleY - 10;
        this.bach18.y = h - this.bach18.getBounds().height * this.bach18.scaleY - 10;
        this.brahms18.y = h - this.brahms18.getBounds().height * this.brahms18.scaleY - 10;
        this.mozart18.y = h - this.mozart18.getBounds().height * this.mozart18.scaleY - 10;
    }

    resize(){
        this.calculate_widths(this.music_stage, 1);
        this.calculate_positions();
        g_stage.update();
    }
}

function initLevel (){
    let level = new IntervalsLevel();
    level.init_game();
    window.addEventListener('resize', level, false);
}