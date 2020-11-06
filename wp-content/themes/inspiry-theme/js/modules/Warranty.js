let $ = jQuery; 
class Warranty{ 
    constructor(){
        this.events(); 
    }

    events(){ 
        $('.bc-single-product__warranty h1').append('<i class="fal fa-plus"></i>');
        $(document).on('click', '.bc-single-product__warranty i', this.showContentIcon  ); 
        $(document).on('click', '.bc-single-product__warranty h1', this.showContent); 
    }

    showContent(e){ 
        $(e.target).closest('h1').next('p').slideToggle(300); 
        $(e.target).find('i').toggleClass('fa-plus');
        $(e.target).find('i').toggleClass('fa-minus');
    }
    showContentIcon(e){ 
        $(e.target).next('p').slideToggle(300)
    }

}

export default Warranty; 