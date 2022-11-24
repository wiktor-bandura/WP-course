class Search {
    constructor() {
        this.addSearchHTML();
        this.openButton = [...document.querySelectorAll('.js-search-trigger')];
        this.closeButton = document.querySelector('.search-overlay__close');
        this.searchOverlay = document.querySelector('.search-overlay');
        this.searchResults = document.querySelector('.search-overlay__results');
        this.searchInput = document.getElementById('search-term');
        this.isOverlayOpen = false;
        this.isSpinnerVisible = false;
        this.prevValue;
        this.events();
        this.typingTimer;
    }

    events = () => {
        this.openButton.forEach(el => {
            el.addEventListener('click', e => {
                e.preventDefault();
                this.openOverlay();
            })
        })
        this.closeButton.addEventListener('click', this.closeOverlay);
        this.searchInput.addEventListener('keyup', this.typicLogic);
        document.addEventListener('keydown', this.keyPressDispatcher);
    }

    openOverlay = () => {
            this.searchOverlay.classList.add('search-overlay--active');
            document.body.classList.add('body-no-scroll');
            this.isOverlayOpen = true;
    }
    closeOverlay = () => {
        this.searchOverlay.classList.remove('search-overlay--active');
        document.body.classList.remove('body-no-scroll');
        this.isOverlayOpen = false;
    }

    keyPressDispatcher = (e) => {
        if(
            e.keyCode === 83 &&
            this.isOverlayOpen === false &&
            document.activeElement !== document.querySelector('input') &&
            document.activeElement !== document.querySelector('textarea'
            ))
                this.openOverlay();
        if(e.keyCode === 27 && this.isOverlayOpen === true)
                this.closeOverlay();
    }

    typicLogic = () => {
        if(this.searchInput.value !== this.prevValue) {
            clearTimeout(this.typingTimer);
            if(this.searchInput.value) {
                if(!this.isSpinnerVisible) {
                    this.searchResults.innerHTML = '<div class="spinner-loader"></div>';
                    this.isSpinnerVisible = true;
                }
                this.typingTimer = setTimeout(this.getResults, 1000);
            } else {
                this.searchResults.innerHTML = '';
                this.isSpinnerVisible = false;
            }
        }
        this.prevValue = this.searchInput.value;
    }

    getResults = async () => {
        const result = await fetch(`${themeData.root_url}/wp-json/university/search?term=${this.searchInput.value}`)
            .then(res => res.json())
            .then((result) => {

                    this.searchResults.innerHTML = `
                    <div class="row">
                        <div class="one-third">
                            <h2 class="search-verlay__section-title">General Info</h2>
                            ${result.generalInfo.length ? '<ul class=" link-list min-list">' : '<p>No general info matches</p>'}
                            ${result.generalInfo.map((item) => `<li><a href="${item.link}">${item.title}</a>
                            ${item.postType === 'post' ? ` by ${item.authorName}` : '' }</li>`).join('')}
                            ${result.generalInfo.length ? '</ul>' : ''}
                        </div>
                        <div class="one-third">
                            <h2 class="search-verlay__section-title">Programs</h2>
                            ${result.programs.length ? '<ul class=" link-list min-list">' : '<p>No programs matches that fraze</p>'}
                            ${result.programs.map((item) => `<li><a href="${item.link}">
                            ${item.title}</a></li>`).join('')}
                            ${result.programs.length ? '</ul>' : ''}
                            <h2 class="search-verlay__section-title">Professors</h2>
                            ${result.professors.length ? '<ul class=" link-list min-list">' : '<p>No matches</p>'}
                            ${result.professors.map((item) => `
                                 <li class="professor-card__list-item">
                                    <a class="professor-card" href="${item.link}">
                                        <img src="${item.image}" alt="Professor thumbnail" class="professor-card__image">
                                        <span class="professor-card__name">${item.title}</span>
                                    </a>
                                </li>
                            `).join('')}
                            ${result.professors.length ? '</ul>' : ''}
                        </div>
                        <div class="one-third">
                             <h2 class="search-verlay__section-title">Events</h2>
                            ${result.events.map((item) => `
                                  ${result.events.length ? '': '<p>No events matches</p>'}
                                <div class="event-summary">
                                    <a class="event-summary__date t-center" href="${item.link}">
                                        <span class="event-summary__month">${item.month}</span>
                                        <span class="event-summary__day">${item.day}</span>
                                    </a>
                                    <div class="event-summary__content">
                                        <h5 class="event-summary__title headline headline--tiny">
                                            <a href="${item.link}">${item.title}</a>
                                        </h5>
                                        <p>
                                            <a href="${item.link}" class="nu gray">Learn more</a>
                                        </p>
                                    </div>
                                </div>

                            `).join('')}
                            <h2 class="search-verlay__section-title">Campuses</h2>
                            ${result.campuses.length ? '<ul class=" link-list min-list">' : '<p>No matches</p>'}
                            ${result.campuses.map((item) => `<li><a href="${item.link}">    
                            ${item.title}</a></li>`).join('')}
                            ${result.campuses.length ? '</ul>' : ''}
                        </div>
                    </div>`;

                    this.isSpinnerVisible = false;
            })
            .catch(err => console.log(err))
    }



    addSearchHTML = () => {
        document.body.innerHTML += `
            <div class="search-overlay">
                <div class="search-overlay__top">
                    <div class="container">
                        <i class="fa fa-search search-overlay__icon" aria-hidden="true"></i>
                            <input type="text" class="search-term" id="search-term" placeholder="What are you looking for?" autocomplete="off">
                        <i class="fa fa-window-close search-overlay__close" aria-hidden="true"></i>
                    </div>
                </div>
                <div class="container">
                    <div class="search-overlay__results"></div>
                </div>
            </div>
        `;
    }
}

export default Search;