
class PuncuationLevel extends AbstractLevel{
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
            {id: "bach19", src: "/images/assets/Bach19.png"},
            {id: "brahms19", src: "/images/assets/Brahms19.png"},
            {id: "vaudeville_stage", src: "/images/assets/Vaudeville_stage.png"},
            {id: "mozart19", src: "/images/assets/Mozart19.png"},
            {id: "strav19", src: "/images/assets/Strav19.png"},
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
        this.music_stage = this.place_image_on_screen("vaudeville_stage", 0, 0);
        g_canvas.width = this.music_stage.getBounds().width;
        g_canvas.height = this.music_stage.getBounds().height;
        //create and place the mixer buttons
        let x = 120;
        this.training_level_button = this.place_image_on_screen("mixer_button", x, 150, this.navigate_buttons.bind(this), "Main Navigation");
        this.training_level_button.fnc_call = "main";
        this.mozart19 = this.place_image_on_screen("mozart19", 0, 0);
        this.brahms19 = this.place_image_on_screen("brahms19", 0, 0);
        this.strav19 = this.place_image_on_screen("strav19", 0, 0);
        this.bach19 = this.place_image_on_screen("bach19", 0, 0);
    }

    calculate_positions(){
        this.bach19.x = this.strav19.x + this.strav19.getBounds().width * this.strav19.scaleX - 65;
        this.brahms19.x = this.bach19.x +  this.bach19.getBounds().width * this.bach19.scaleX - 65;
        this.mozart19.x = this.brahms19.x + this.brahms19.getBounds().width * this.brahms19.scaleX - 65;

        let h = this.music_stage.getBounds().height * this.music_stage.scaleY;
        this.strav19.y = h - this.strav19.getBounds().height * this.strav19.scaleY - 50;
        this.bach19.y = h - this.bach19.getBounds().height * this.bach19.scaleY - 10;
        this.brahms19.y = h - this.brahms19.getBounds().height * this.brahms19.scaleY - 10;
        this.mozart19.y = h - this.mozart19.getBounds().height * this.mozart19.scaleY - 10;
    }

    resize(){
        this.calculate_widths(this.music_stage, 1);
        this.calculate_positions();
        g_stage.update();
    }
}

function initLevel (){
    let level = new PuncuationLevel();
    level.init_game();
    window.addEventListener('resize', level, false);
}