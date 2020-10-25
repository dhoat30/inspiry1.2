let $ = jQuery;
//wishlist
class WishlistAjax{
    constructor(){ 
       
        this.aClick = document.querySelectorAll('.bc-wish-list-item-anchor');
        
        this.createBtn = document.querySelector('.bc-wish-list-btn--new'); 
        this.events();
    }

    //events 
    events(){
        
        //this.createBtn.addEventListener('click',this.createWishlist.bind(this) ); 

        this.aClick.forEach(event=>{
            event.addEventListener('click', this.runAjax.bind(this)); 
        });
    }

    //functions
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