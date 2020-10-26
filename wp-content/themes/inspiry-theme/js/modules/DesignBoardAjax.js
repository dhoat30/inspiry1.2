
let $ = jQuery; 

class DesignBoard{ 
    constructor(){ 
       this.events(); 
    }

    //events
    events(){ 
        //show and hide option icon
        $('.board-card').mouseenter(this.showOptionIcon.bind(this)); 
        $('.board-card').mouseleave(this.hideOptionIcon.bind(this)); 
        
        //show options on click
        $('.board-card .option-icon').on('click', this.showOptions.bind(this));
       $(document).mouseup(this.hideOptionContainer.bind(this));

       $('.board-card-archive .option-icon').on('click', this.showOptionsArchive.bind(this));
       $(document).mouseup(this.hideOptionContainerArchive.bind(this));


       //show share icon container
       $('.share-btn').on('click', this.showShareContainer);
       $('.share-icon-container span').on('click', this.hideShareContaienr); 
        
        //board page
        $('.board-card-archive').mouseenter(this.showOptionIconArchive.bind(this)); 
        $('.board-card-archive').mouseleave(this.hideOptionIconArchive.bind(this)); 

    }

    //functions 
    //share container
    hideShareContaienr(){ 
        $('.share-icon-container').hide(300);
        $('.overlay').fadeOut(300);   

    }
    showShareContainer(e){ 
        var shareContainer = $('.share-icon-container')
        shareContainer.show(300);
        $('.overlay').fadeIn(300);   
    }

    //show & hide options 
    hideOptionContainer(e){ 
        var pinOptionContainer = $('.pin-options-container');
        if (!pinOptionContainer.is(e.target) && pinOptionContainer.has(e.target).length === 0) 
        {
            pinOptionContainer.hide(300); 
        }
    }
    showOptions(e){ 
        var pinOptionContainer = $(e.target).closest('.board-card').find('.pin-options-container');
        pinOptionContainer.show(300);
    }

    hideOptionContainerArchive(e){ 
        var pinOptionContainer = $('.pin-options-container');
        if (!pinOptionContainer.is(e.target) && pinOptionContainer.has(e.target).length === 0) 
        {
            pinOptionContainer.hide(300); 
        }
    }
    showOptionsArchive(e){ 
        var pinOptionContainer = $(e.target).closest('.board-card-archive').find('.pin-options-container');
        pinOptionContainer.show(300);
    }


    //show option icon
    showOptionIcon(e){ 
        e.preventDefault(); 
       
        var optionSelector = $(e.target).closest('.board-card').find('.option-icon');
        optionSelector.show();
    }

    hideOptionIcon(e){ 
        e.preventDefault(); 
        var optionSelector = $(e.target).closest('.board-card').find('.option-icon');
        optionSelector.hide();
    }
    //board archive
   showOptionIconArchive(e){ 
       console.log('hover');
       var optionSelector = $(e.target).closest('.board-card-archive').find('.option-icon');
       optionSelector.show();
   }
   hideOptionIconArchive(e){ 
    e.preventDefault(); 
    var optionSelector = $(e.target).closest('.board-card-archive').find('.option-icon');
    optionSelector.hide();
}
}

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
      
        
        let url = $(e.target).closest('a').attr('href');
        console.log(url);

        var xhr = new XMLHttpRequest();

        xhr.open('GET', url, true); 
        console.log('processing');
        document.getElementsByClassName('ajax-result').innerHTML = " ";

        xhr.onload = function(){ 
            $('.ajax-result').append(this.responseText);
            const add = new DesignBoard();
        }

        xhr.send();
        
    }
}

export default DesignBoardAjax; 