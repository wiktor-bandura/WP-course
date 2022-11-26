class Like {
    constructor() {
        if(document.querySelector('.like-box')) {
            this.likeBox = document.querySelector('.like-box');
            this.events();
        }
    }

    events() {
        this.likeBox.addEventListener('click', this.clickDispatcher)
    }

    clickDispatcher = (e) => {

        const currentLikeBox = e.target.closest('.like-box');

        console.log(currentLikeBox);

        if(this.likeBox.getAttribute('data-exists') === 'yes') {
            this.deleteLike();
        } else {
            this.createLike();
        }
    }

    createLike = () => {
        console.log('CREAT')
    }

    deleteLike = () => {
        console.log('DEL')
    }
}

export default Like;