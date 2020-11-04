let $ = jQuery;
class LocationPage{ 
    constructor(){ 
        this.events(); 
    }
    events(){ 
        $('.trade-directory .main-cards .flex .card').hover(this.showElements, this.hideElements);
    }

    showElements(e){ 
        console.log($(e.target).closest('.card').find('.website-link').html());
        $(e.target).closest('.card').find('.website-link').css('opacity','1'); 
        $(e.target).closest('.card').find('.design-board-save-btn-container').css('opacity','1'); 

    }
    hideElements(e){ 
        $(e.target).closest('.card').find('.website-link').css('opacity','0'); 
        $(e.target).closest('.card').find('.design-board-save-btn-container').css('opacity','0'); 


    }
}

export default LocationPage; 