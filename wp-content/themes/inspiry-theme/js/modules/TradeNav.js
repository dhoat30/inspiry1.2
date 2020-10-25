class TradeNav{ 
    constructor(){ 
        this.nav();
    }

    nav(){ 
          //trade nav 
    
    var header = document.querySelector(".trade-nav-container .nav");
    var listItems = header.getElementsByClassName("trade-nav-link");
    for (var i = 0; i < listItems.length; i++) {
      listItems[i].addEventListener("click", function() {
      var current = document.getElementsByClassName("active-nav");
      current[0].className = current[0].className.replace(" active-nav", "");
        this.className += " active-nav";
        });
        }

    let tradeNavLink = document.querySelectorAll('.trade-nav-link'); 

    tradeNavLink.forEach((val)=>{
        val.addEventListener('click',(e)=>{
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
        })
    })
    }
}

export default TradeNav;