class CarouselTouchPlugin {

  constructor (carousel) {
    carousel.container.addEventListener('dragstart', e => e.preventDefault());
    carousel.container.addEventListener('mousedown', this.startDrag.bind(this));
    carousel.container.addEventListener('touchstart', this.startDrag.bind(this));
    window.addEventListener('mousemove', this.drag.bind(this));
    window.addEventListener('touchmove', this.drag.bind(this));
    window.addEventListener('touchend', this.endDrag.bind(this));
    window.addEventListener('mouseup', this.endDrag.bind(this));
    window.addEventListener('touchcancel', this.endDrag.bind(this));
    this.carousel = carousel;
  }

  startDrag (e) {
    if (e.touches) {
      if (e.touches.length > 1) {
        return;
      } else {
      e = e.touches[0];
      } 
    }
    this.origin = {x: e.screenX, y: e.screenY};
    this.width = this.carousel.containerWidth;
    this.carousel.disableTransition();
  }

  drag (e) {
    if (this.origin) {
      let point = e.touches ? e.touches[0] : e;
      let translate = {x: point.screenX - this.origin.x, y: point.screenY - this.origin.y};
      if (e.touches && Math.abs(translate.x) > Math.abs(translate.y)) {
        e.preventDefault();
        e.stopPropagation();
      }
      let baseTranslate = this.carousel.currentItem * -100 / this.carousel.items.length;
      this.lastTranslate = translate;
      this.carousel.translate(baseTranslate + 100 * translate.x / this.width);
    }
  }

  endDrag (e) {
    if (this.origin && this.lastTranslate) {
      this.carousel.enableTransition();
      if (Math.abs(this.lastTranslate.x / this.carousel.carouselWidth) > 0.2) {
        if (this.lastTranslate.x < 0) {
          this.carousel.next();
        } else {
          this.carousel.prev();
        }
      } else {
        this.carousel.goToItem(this.carousel.currentItem);
      }
    }
    this.origin = null;
  }

}

class Carousel {

  constructor (element, options = {}) {
    this.element = element;
    this.options = Object.assign({}, {
      slidesToScroll: 1,
      slidesVisible: 1,
      loop: false,
      pagination: false,
      navigation: true,
      infinite: false,
    }, options);

    if (this.options.loop && this.options.infinite) {
      throw new Error("Un carousel ne peut être à la fois en boucle et en infini");
    }

    this.isMobile = false;
    this.currentItem = 0;
    this.moveCallbacks = [];
    this.offset = 0;

    this.container = document.querySelector('.carousel-gamestore__container');
    this.root = document.querySelector('.carousel-gamestore');
    this.items = Array.from(document.querySelectorAll('.carousel-gamestore__item'));

    if (this.options.infinite === true) {
      this.offset = this.options.slidesVisible + this.options.slidesToScroll;
      if (this.offset > this.items.length) {
        console.error("Vous n'avez pas assez d'éléments dans votre carousel", element);
      }
      this.items = [
        ...this.items.slice(this.items.length - this.offset).map(item => item.cloneNode(true)),
        ...this.items,
        ...this.items.slice(0, this.offset).map(item => item.cloneNode(true))
      ]
      this.goToItem(this.offset, false);
    }
    this.items.forEach(item => this.container.appendChild(item));

    this.setStyle();
    if (this.options.navigation === true) {
      this.createNavigation();
    }

    if (this.options.pagination === true) {
      this.createPagination();
    }

    //Evenements
    this.moveCallbacks.forEach(cb => cb(this.currentItem));
    this.onWindowResize();
    window.addEventListener('resize', this.onWindowResize.bind(this));
    this.root.addEventListener('keyup', e => {
      if (e.key === 'ArrowRight' || e.key === 'Right') {
        this.next();
      } else if (e.key === 'ArrowLeft' || e.key === 'Left') {
        this.prev();
      }
    });
    if (this.options.infinite === true) {
      this.container.addEventListener('transitionend', this.resetInfinte.bind(this));
    }
    new CarouselTouchPlugin(this);
  }

  setStyle() {
    let ratio = this.items.length / this.slidesVisible;
    this.container.style.width = (ratio * 100) + '%';
    this.items.forEach(item => item.style.width = ((100 / this.slidesVisible) / ratio) + '%');
  };

  createNavigation () {
    let nextButton = this.createDivWithClass('carousel-gamestore__next');
    let prevButton = this.createDivWithClass('carousel-gamestore__prev');
    this.root.appendChild(nextButton);
    this.root.appendChild(prevButton);
    nextButton.addEventListener('click', this.next.bind(this));
    prevButton.addEventListener('click', this.prev.bind(this));
    if (this.options.loop === true) {
      return;
    }
    this.onMove(index => {
      if (index === 0) { 
        prevButton.classList.add('carousel-gamestore__prev--hidden');
      } else {  
        prevButton.classList.remove('carousel-gamestore__prev--hidden');
      }
      if (this.items[this.currentItem + this.slidesVisible] === undefined) {
        nextButton.classList.add('carousel-gamestore__next--hidden');
      } else {
        nextButton.classList.remove('carousel-gamestore__next--hidden');
      }
    });
  }

  createPagination () {
    const pagination = document.querySelector('.pagination-pacman');
    const buttons = [];
    for (let i = 0; i < (this.items.length - 2 * this.offset); i = i + this.options.slidesToScroll) {
      const button = document.createElement('input');
      button.classList.add('input-pacman');
      button.id = `dot-${i+1}`;
      button.type = 'radio';
      button.name = 'dots';
      if (i === 0) {
        button.checked = 'checked';
      }
      button.addEventListener('change', () => this.goToItem(i + this.offset));
      pagination.appendChild(button);
      buttons.push(button);

      const label = document.createElement('label');
      label.classList.add('label-pacman');
      label.htmlFor = `dot-${i+1}`;
      pagination.appendChild(label);
    };
    const pacman = document.createElement('div');
    pacman.classList.add('pacman');
    pagination.appendChild(pacman);

    this.onMove(index => {
      const count = this.items.length - 2 * this.offset;
      const activeButton = buttons[Math.floor(((index - this.offset) % count)/ this.options.slidesToScroll)];
      if (activeButton) {
        activeButton.checked = true;
      }
    });  
  } 

  translate (percent) {
    this.container.style.transform = 'translate3d(' + percent + '%, 0, 0)';
  }


  next() {
    this.goToItem(this.currentItem + this.slidesToScroll);
  }

  prev() {
    this.goToItem(this.currentItem - this.slidesToScroll);
  }

  goToItem(index, animation = true) {
    if (index < 0) {
      if (this.options.loop) {
        index = this.items.length - this.slidesVisible;
      } else {
        return;
      }
      index = this.items.length - this.options.slidesVisible;
    } else if (index >= this.items.length || (this.items[this.currentItem + this.slidesVisible] === undefined && index > this.currentItem)) {
      if (this.options.loop) {
        index = 0;
      } else {
        return;
      }
    }
    let translateX = index * -100 / this.items.length;
    if (animation === false) {
      this.disableTransition();
    }
    this.translate(translateX);
    this.container.offsetHeight;
    if (animation === false) {
      this.enableTransition();
    }
    this.currentItem = index;
    this.moveCallbacks.forEach(cb => cb(index));
  }

  resetInfinte() {
    if (this.currentItem <= this.options.slidesToScroll) {
      this.goToItem(this.currentItem + (this.items.length - 2 * this.offset), false);
    } else if (this.currentItem >= this.items.length - this.offset) {
      this.goToItem(this.currentItem - (this.items.length - 2 * this.offset), false);
    }
  }  

  onMove(cb) {
    this.moveCallbacks.push(cb);
  }

  onWindowResize() {
    let mobile = window.innerWidth < 800;
    if (mobile !== this.isMobile) {
      this.isMobile = mobile;
      this.setStyle();
      this.moveCallbacks.forEach(cb => cb(this.currentItem));
    }
  }

  createDivWithClass(className) {
    let div = document.createElement('div');
    div.setAttribute('class', className);
    return div;
  }

  disableTransition() {
    this.container.style.transition = 'none';
  }

  enableTransition() {
    this.container.style.transition = '';
  }

  get slidesToScroll() {
    return this.isMobile ? 1 : this.options.slidesToScroll;
  }

  get slidesVisible() {
    return this.isMobile ? 1 : this.options.slidesVisible;
  }

  get containerWidth() {
    return this.container.offsetWidth;
  }

  get carouselWidth() {
    return this.root.offsetWidth;
  }

}





document.addEventListener('DOMContentLoaded', function() {
  new Carousel(document.querySelector('#carousel-gamestore'), {
  slidesToScroll: 1,
  slidesVisible: 1,
  loop: false,
  pagination: true,
  navigation: false,
  infinite: true,
  navigation: false
  });
});