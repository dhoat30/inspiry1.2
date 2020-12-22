let $ = jQuery;
class LocationPage{ 
    constructor(){ 
        this.events(); //window.location.hostname
    }
    events(){ 
        let url = [];
        $('.trade-directory .main-cards .flex .card').hover(this.showElements, this.hideElements);
         url = $(document).find('.trade-directory .main-cards .flex .card .logo img').attr('data-src');
      
    }

    showElements(e){ 
        $(e.target).closest('.card').find('.website-link').css('opacity','1'); 
        $(e.target).closest('.card').find('.design-board-save-btn-container').css('opacity','1'); 

    }
    hideElements(e){ 
        $(e.target).closest('.card').find('.website-link').css('opacity','0'); 
        $(e.target).closest('.card').find('.design-board-save-btn-container').css('opacity','0'); 


    }
}

export default LocationPage; 