//import '../style.css';
let $ = jQuery; 
import WishlistAjax from './modules/WishlistAjax'; 
import Warranty from './modules/Warranty'; 
import WallpaperCalc from './modules/WallpaperCalc'; 
import LayBuy from './modules/LayBuy'; 
import TradeNav from './modules/TradeNav';
import DesignBoard from './modules/DesignBoard'; 
import DesignBoardSaveBtn from './modules/DesignBoardSaveBtn';
import DesignBoardAjax from './modules/DesignBoardAjax'; 
import WishlistAjaxBp from './modules/WishlistAjaxBp';
import FacetWp from './modules/FacetWp';  
import LogIn from './modules/LogIn'; 
import Overlay from './modules/overlay'; 
import LocationPage from './modules/LocationPage'; 
import TopNav from './modules/TopNav';
import GeoTradeSearch from './modules/GeoTradeSearch';
import ShopFav from './modules/ShopFav'; 
window.onload = function() {

    const shopFav = new ShopFav(); 
    const geoTradeSearch = new GeoTradeSearch(); 
    const topnav = new TopNav(); 
    const locationPage = new LocationPage(); 
    const overlay = new Overlay();
    const designBoardSinglePage = new DesignBoard(); 
    const designBoardSaveBtn = new DesignBoardSaveBtn();


let designBoardAjax = new DesignBoardAjax(); 

const tradeNav = new TradeNav();

//slogan 

$('.logo-container .slogan').css('opacity', '1');


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




//log in 
const logIn = new LogIn();
//facet wp
const facetWp = new FacetWp();  

//const wishlistAjaxBp = new WishlistAjaxBp();
const wishlistAjax = new WishlistAjax();
const warranty = new Warranty(); 
const wallpaperCalc = new WallpaperCalc(); 
const laybuy = new LayBuy();



  





