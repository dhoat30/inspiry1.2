let $ = jQuery;
//wishlist
class WishlistAjax{
    constructor(){ 
       
        this.aClick = document.querySelectorAll('.bc-wish-list-item-anchor');
        this.createBtn = document.querySelector('.bc-wish-list-btn--new'); 
        this.wishListIcon = document.querySelectorAll('.wish-list-icon-container .fa-heart'); 
        this.closeIcon = $('.bc-pdp-wish-lists .fa-times');
        this.events();
    }

    //events 
    events(){
        
        this.aClick.forEach(event=>{
            event.addEventListener('click', this.runAjax.bind(this)); 
        });
        
        $(document).on('click', '.wish-list-icon-container .fa-heart', this.showListContainer );

        this.closeIcon.on('click', this.hideContainer); 
    }

    

    //functions
    //hide container
    hideContainer(e){ 
        $(e.target).closest('.overlay').hide(300);
    }
    //show list container
    showListContainer(e){ 
        console.log('whish list');
        $(e.target).find('.overlay').show(300);
        $(e.target).find('.bc-pdp-wish-lists').show(300);
      
       
    }
/*
    
    createWishlist(e){ 
        $(document).on('submit', '#create-wl-form', (e)=> {
            e.preventDefault();
            let actionURL = $('#create-wl-form').attr('action'); 
            //Ajax request 
        var xhr = new XMLHttpRequest(); 
    
        xhr.open('POST', actionURL, true); 
        
        //adding the loader icon

        xhr.onload = function (){ 
            //removing the loader icon
            //displaying the loader icon
           console.log('success')
        }
    
        xhr.send();
        })
    }
*/
    runAjax(e){ 
        e.preventDefault(); 
       
        let wishURL = e.path[0].href; 
        
        //Ajax request 
        var xhr = new XMLHttpRequest(); 
    
        xhr.open('GET', wishURL, true); 
        
        //adding the loader icon
        document.querySelector('.loader-icon').style.display = 'inline-block'; 

        xhr.onload = function (){ 
            //removing the loader icon
            document.querySelector('.loader-icon').style.display = 'none'; 
            //displaying the loader icon
            document.querySelector('.loader-confirmation').style.display = 'block';
           console.log('success')
        }

        document.querySelector('.loader-confirmation').style.display = 'none';

    
        xhr.send();
    }
}

export default WishlistAjax; 