

let $ = jQuery; 




/* ajax call for design board */ 

class WishlistAjaxBp{ 
    constructor(){ 
        this.aTag = document.querySelectorAll('#buddypress .bc-wish-list-link'); 
        this.createButton = document.querySelector('#buddypress .bc-wish-list-btn--new');

        this.events(); 
    }

    events(){ 
        //show list items
        this.aTag.forEach(e=>{
         e.addEventListener('click', this.getWishlistItems);
        })

        this.createButton.addEventListener('click',this.createWishlist.bind(this) ); 
        
        
        
        //prevent create new button to redirect
        //this.newListFrom.submit(this.createNewList);  
    }

    createWishlist(e){ 
        $(document).on('submit', '#create-wl-form', (e)=> {
            e.preventDefault();
            let actionURL = $('#create-wl-form').attr('action'); 
            console.log(actionURL);
            //get values 
            let name = $('#wish-list-name-new').val();
            let value = $('#wish-list-public-new').val(); 
            console.log(value);
            let params = `name=${name}&public=${value}`;

            //ajax call
            var xhr = new XMLHttpRequest();

            xhr.open('POST', actionURL, true); 
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

            xhr.onload = function(){ 
                console.log(this.status);
            }
            xhr.send(params); 
        })

    }

    getBoards(e){ 
        e.preventDefault(); 
        //get a url 
        let url = $(e.target).closest('a').attr('href');
            //creat an xhr object 
            var xhr = new XMLHttpRequest();


            //send get request 
            xhr.open('GET', url, true); 
            //show loader
            $(e.target).append('<div class="loader-div" style="display:block"></div>'); 
            e.target.querySelector('.loader-div').classList.add('loader-icon');
            
            //get results and show theme 
            xhr.onload = function(){   
                //hide loader 
                $('.loader-div').removeClass('loader-icon');

                //hide all lists
                $('.buddypress-wishlist').hide(300);
                //show single list
               $('.wishlsit-single-bp').append(this.responseText);

               //hide single list and show all lists
               $('.bc-all-wish-lists').on('click', (e)=>{
                e.preventDefault(); 

                $('.wishlsit-single-bp').html('');
                $('.buddypress-wishlist').show(300);

               }); 
            }
            //send request 
                xhr.send();

                    
        
            
     
       
        
    }

}

export default WishlistAjaxBp; 