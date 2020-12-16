let $ = jQuery; 

class ToolTip {
    constructor(){
        this.events(); 
    }
    events(){
        $('.prof-title').hover(this.projectPage); 
    }

    projectPage(e){
        console.log(23); 
    }
}

export default ToolTip; 