let $ = jQuery; 

class ToolTip {
    constructor(){
        $('.design-board-save-btn-container').append(`
                <div class="tooltips roboto-font font-s-regular box-shadow">
                    Save to design board
                </div>`); 
        this.events(); 
    }
    events(){
        $('.design-board-save-btn-container i').hover(this.showTooltip, this.hideTooltip); 
    }

    showTooltip(e){
        $(e.target).siblings('.tooltips').slideDown('200'); 
        console.log(23);
    }
    hideTooltip(e){
        $('.tooltips').hide();
    }
}

export default ToolTip; 