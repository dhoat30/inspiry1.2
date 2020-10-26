import DesignBoard from './DesignBoard';
import DesignBoardSaveBtn from './DesignBoardSaveBtn'; 

let $ = jQuery; 




/* ajax call for design board */ 

class DesignBoardAjax{ 
    constructor(){ 
        this.aTag = document.querySelectorAll('.design-board-card'); 
       
        this.events(); 
    }

    events(){ 
      
       
        this.aTag.forEach(e=>{
         e.addEventListener('click', this.getBoards.bind(this));
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
        
        $(e.target).closest('a').append('<div class="loader-div" style="display:block"></div>'); 
        e.target.closest('a').querySelector('.loader-div').classList.add('loader-icon');

        //get results and show theme 
        xhr.onload = function(){ 
            e.target.closest('a').querySelector('.loader-div').classList.remove('loader-icon');
            $('.body-container').hide(300);
            $('.ajax-result').show(300);
            $('.ajax-result').append('<i class="fal fa-arrow-left"></i>');
            $('.ajax-result').append(this.responseText);
            const overlay = new DesignBoard();
            const restApiCalls = new DesignBoardSaveBtn();

            $('.fa-arrow-left').on('click', ()=>{
                console.log('clsoe icon');
                $('.body-container').show(300);
                $('.ajax-result').hide(300);
                $('.ajax-result').html(' ');
            })
        }
        //send request 
            xhr.send();
        
            
     
       
        
    }
}

export default DesignBoardAjax; 