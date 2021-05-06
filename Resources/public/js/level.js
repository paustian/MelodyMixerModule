
class MelodyMixerLevel extends AbstractLevel {
    handleEvent(evt) {
        switch (evt.type) {
            case "resize":
                this.resize();
        }

    }
    resize(){
        g_stage.update();
    }

    calculate_positions(){
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

    let mixer = new MelodyMixerLevel();
    //I probably can just change this back to a number
    gLevelId = levelId;
    mixer.init_game();
    g_midiPlayer = MidiPlayer;
    window.addEventListener('resize', mixer, false);
}