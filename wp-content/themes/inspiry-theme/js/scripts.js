import '../style.css';
let $ = jQuery; 
import WishlistAjax from './modules/WishlistAjax'; 
import Warranty from './modules/Warranty'; 
import WallpaperCalc from './modules/WallpaperCalc'; 
import LayBuy from './modules/LayBuy'; 
import TradeNav from './modules/TradeNav';
import DesignBoard from './modules/DesignBoard'; 
import DesignBoardSaveBtn from './modules/DesignBoardSaveBtn';
import DesignBoardAjax from './modules/DesignBoardAjax'; 
 

window.onload = function() {

    const designBoardSinglePage = new DesignBoard(); 
   const designBoardSaveBtn = new DesignBoardSaveBtn();
   
   
let designBoardAjax = new DesignBoardAjax(); 
   
const tradeNav = new TradeNav();

//profile navbar


   let profileNavbar = {
       eventListener: function (){ 
        $('.profile-name-value').click(function(e){
            let user = document.querySelector('.profile-name-value').innerHTML;  
            console.log("click working");
            if(user.includes('LOGIN / REGISTER'))
            { 
                console.log('Log In'); 
            }
            else{ 
                e.preventDefault(); 
                $('.my-account-nav').slideToggle(200, function(){ 
                    $('.arrow-icon').toggleClass('fa-chevron-up');
                }); 
            }
            
    })
       }
   }

   profileNavbar.eventListener();
    

    
    
   

}



    








const wishlistAjax = new WishlistAjax();
const warranty = new Warranty(); 
const wallpaperCalc = new WallpaperCalc(); 
const laybuy = new LayBuy();



  





