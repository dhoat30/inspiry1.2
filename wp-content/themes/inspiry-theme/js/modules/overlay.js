let $ = jQuery;
class Overlay{ 
    constructor(){ 
        this.events(); 
    }

    events(){ 
        $('.featured-project-section .flex .card').hover((e)=>{
            console.log('hover')
            console.log(e.target);
            $(e.target).css('opacity', '60%');
            $(e.target).siblings('.featured-project-section .flex .column-s-font').show(300); 
        }, (e)=>{ 
            $(e.target).css('opacity', '0');
            $(e.target).siblings('.featured-project-section .flex .column-s-font').hide(300);


        })
    }
}

export default Overlay; 