import axios from "axios";

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

        if(this.likeBox.getAttribute('data-exists') === 'yes') {
            this.deleteLike(currentLikeBox);
        } else {
            this.createLike(currentLikeBox);
        }
    }

    createLike = async (box) => {
        try {
            const response = await axios.post(`${themeData.root_url}/wp-json/university/managelike`, {
                professorId: box.getAttribute('data-professor'),
            });
            console.log(response.data);

        } catch(err) {
            console.log(err);
        }
    }

    deleteLike = async (box) => {
        try {
            const response = await axios.delete(`${themeData.root_url}/wp-json/university/managelike`);
            console.log(response.data);

        } catch(err) {
            console.log(err);
        }
    }
}

export default Like;