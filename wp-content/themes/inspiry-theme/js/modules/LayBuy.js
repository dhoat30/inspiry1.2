//laybuy event 
class Laybuy{
    constructor(){ 
        this.laybuyBtn = document.querySelector('.lay-buy-open'); 
        this.laybuyCloseBtn = document.querySelector('.close-laybuy');
        this.events(); 
    }

    events(){ 
        if(this.laybuyBtn){
            this.laybuyBtn.addEventListener('click', this.openLaybuy); 

        }
        if(this.laybuyCloseBtn){
            this.laybuyCloseBtn.addEventListener('click', this.closeLaybuy); 

        }
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