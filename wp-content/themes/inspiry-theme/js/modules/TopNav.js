let $ = jQuery; 
class TopNav{ 
    constructor(){ 
        this.events(); 
    }
    events(){ 
        $('#top-navbar a').hover(this.showSubNav, this.hideSubnav); 
        $('.design-services').hover(this.showSubNav); 
    }
    showSubNav(e){ 
        let designBoardHover = $(e.target).closest('#design-services'); 
        console.log(designBoardHover)
        let linkHTML = $(e.target).html();
        if(linkHTML == 'Design Services'){
            $('.design-services').show(300);
        }
        
    }
    hideSubnav(e){ 
        console.log(e)
        

        let designBoardHover = $(e.target).closest('#design-services'); 
        console.log(designBoardHover)
        let linkHTML = $(e.target).html();
        if(linkHTML == 'Design Services'){
            $('.design-services').hide(1000);
        }
        
    }
}

export default TopNav; 