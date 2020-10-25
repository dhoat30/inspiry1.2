let $ = jQuery; 
class Warranty{ 
    constructor(){ 
        this.btn = $('.bc-single-product__warranty h1'); 
        
        this.events(); 
    }

    events(){ 
        //add plus sign
        this.btn.append('<i class="fal fa-plus"></i> ');
        //show p
       // this.btn.on('click', this.showPara.bind(this)); 
    }
    /*

    showPara(e){ 
        let value = e.target.innerHTML; 
        
        if(value.includes('Care Code<i class="fal fa-plus" aria-hidden="true"></i> ')){ 
            console.log(e);
            $('.bc-single-product__warranty p').slideDown(500);
           
            console.log( 'care code');
        }
        else if( value.includes('Care Code Guide')){ 
            console.log($('.bc-single-product__warranty p:nth-child(3)').innerHTML); 
        }
        else if(value.includes('Colour')){ 
            console.log('colour');
        }
    }*/
}

export default Warranty; 