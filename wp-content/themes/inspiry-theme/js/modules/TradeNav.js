let $ = jQuery;
class TradeNav{ 
    constructor(){ 
        this.header = document.querySelector(".trade-nav-container .nav");
        this.listItems = document.getElementsByClassName("trade-nav-link");
        this.current = document.getElementsByClassName("active-nav");
        this.tradeNavLink = document.querySelectorAll('.trade-nav-link'); 

       
        this.events(); 
    }

    events(){ 
        

        this.tradeNavLink.forEach(e=>{
            e.addEventListener('click', this.nav.bind(this)); 
        });
    }

    activeNav(){ 
         this.current[0].className = current[0].className.replace(" active-nav", "");
        this.className += " active-nav";
       
    }

    nav(e){ 
          //trade nav 
            console.log( this.tradeNavLink);

            //remove class
           $('.trade-nav-link').removeClass('active-nav');

        //add class
           $(e.target).addClass('active-nav');
           
            if(e.target.innerHTML == "Profile"){ 
                document.querySelector('.trade-about-nav-content').style.display = "block";
                document.querySelector('.trade-contact-nav-content').style.display = "none";
                document.querySelector('.trade-project-nav-content').style.display = "none"; 
                document.querySelector('.trade-gallery-nav-content').style.display = "none"; 
            }
            else if(e.target.innerHTML == "Contact"){ 
                
                document.querySelector('.trade-about-nav-content').style.display = "none";
                document.querySelector('.trade-project-nav-content').style.display = "none"; 
                document.querySelector('.trade-contact-nav-content').style.display = "block"; 
                document.querySelector('.trade-gallery-nav-content').style.display = "none"; 

            }
            else if(e.target.innerHTML == "Projects"){ 
                document.querySelector('.trade-about-nav-content').style.display = "none";
                document.querySelector('.trade-contact-nav-content').style.display = "none"; 
                document.querySelector('.trade-project-nav-content').style.display = "block"; 
                document.querySelector('.trade-gallery-nav-content').style.display = "none"; 

            }
            else if(e.target.innerHTML == "Gallery"){ 
                document.querySelector('.trade-about-nav-content').style.display = "none";
                document.querySelector('.trade-contact-nav-content').style.display = "none"; 
                document.querySelector('.trade-project-nav-content').style.display = "none"; 
                document.querySelector('.trade-gallery-nav-content').style.display = "block"; 

            }
            
      
    }
}

export default TradeNav;