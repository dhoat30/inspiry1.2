let $ = jQuery; 
class LogIn{ 
    constructor(){ 
        this.events(); 
    }
    events(){ 
        $('.login-tag').on('click', this.showLogInForm); 
    }
   
     
        
  

    showLogInForm(e){ 

        e.preventDefault(); 

        //getting the url
        let url = '<?php echo get_site_url(); ?>/ajax-log-in/'
        console.log(url);
  
          //creat an xhr object 
          var xhr = new XMLHttpRequest();


          //send get request 
          xhr.open('GET', url, true);   
          $(e.target).closest('a').html('<div class="loader-div" style="display:block"></div>');
          e.target.querySelector('.loader-div').classList.add('loader-icon');

          //get results and show theme 
          xhr.onload = function(){ 
            $('.login-overlay').show(300);

            $(e.target).closest('a').html('LOGIN / REGISTER');
            
         
            $('.form-content').append(this.responseText);

           

               //register form popup
               

            
            $('.login-overlay .fa-times').on('click', ()=>{ 
                $('.login-overlay').hide(300);
                $('.form-content').html('');
            })
          }
          //send request 
              xhr.send();
    }
  
    
}
export default LogIn; 