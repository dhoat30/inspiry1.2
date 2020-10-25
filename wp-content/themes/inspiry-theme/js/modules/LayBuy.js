//laybuy event 
class Laybuy{
    constructor(){ 
        this.laybuyBtn = document.querySelector('.lay-buy-open'); 
        this.laybuyCloseBtn = document.querySelector('.close-laybuy');
        this.events(); 
    }

    events(){ 
        this.laybuyBtn.addEventListener('click', this.openLaybuy); 
        this.laybuyCloseBtn.addEventListener('click', this.closeLaybuy); 
    } 

    openLaybuy(){ 
        console.log('laybuy clicked');
        document.getElementById('laybuy-popup').style.display ="flex"; 
    }

    closeLaybuy(){ 
        document.getElementById('laybuy-popup').style.display ="none";
    }
}
export default Laybuy; 