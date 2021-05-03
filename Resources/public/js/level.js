
class MelodyMixerLevel extends AbstractLevel {
    handleEvent(evt) {
        switch (evt.type) {
            case "resize":
                this.resize();
        }

    }
    resize(){
        /*let ow = g_canvas.width;
        if(window.innerWidth < 500){
            g_canvas.width = 500;
        } else if(window.innerWidth > gMaxWidth) {
            g_canvas.width = gMaxWidth;
        } else {
            g_canvas.width = window.innerWidth;
        }
        g_stage.scaleX = g_canvas.width/ow;
        g_canvas.height = window.innerHeight * g_stage.scaleX;
        g_stage.scaleY = g_canvas.scaleX;*/
        g_stage.update();
    }

    calculate_positions(){
        /*this.bach_pauer.x = 200;
        let h = this.music_stage.getBounds().height * this.music_stage.scaleY;
        this.bach_pauer.y = h - this.bach_pauer.getBounds().height * this.bach_pauer.scaleY - 10;*/
    }

    bach_clicked(){
        if(!this.bach_text.visible){
            //display the text
            this.bach_text.x = this.bach_pauer.x - 120;
            this.bach_text.y = this.bach_pauer.y - 40;
            this.bach_text.visible = true;
            g_stage.addChild(this.bach_text);
        } else {
            this.bach_text.visible = false;
            g_stage.removeChild(this.bach_text);
        }
        g_stage.update();
    }
}



function initLevel (levelId){

    g_mixer = new MelodyMixerLevel();
    //I probably can just change this back to a number
    gLevelId = levelId;
    g_mixer.init_game();
    g_midiPlayer = MidiPlayer;
    window.addEventListener('resize', g_mixer, false);
}