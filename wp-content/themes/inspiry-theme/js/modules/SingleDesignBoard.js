let $ = jQuery;

class SingleDesignBoards {
    constructor() {
        this.events();
    }

    events() {
        $('.action-btn-container .share').on('click', () => {
            $('.action-btn-container .share-icons').show();
        })

        $('.action-btn-container .share-icons .fa-times').on('click', () => {
            $('.action-btn-container .share-icons').hide();
        })

        //single card share board
        $('.single-board .board-card .share-btn').on('click', this.showCardShareContainer.bind(this));

    }

    showCardShareContainer(e) {
        let container = $(e.target).closest('.dark-grey').siblings('.share-icon-container');
        container.show();
        $(e.target).closest('.dark-grey').siblings('.overlay').show();
        $(e.target).closest('.dark-grey').siblings('.share-icon-container').find('.close-icon').on('click', () => {
            container.hide();
            $('.overlay').hide();

        })
    }
}

export default SingleDesignBoards;