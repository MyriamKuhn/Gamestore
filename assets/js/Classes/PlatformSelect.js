import { LocationSelect } from './LocationSelect.js';
import { secureInput } from '../utils.js';

export class PlatformSelect {

  constructor(gameDatas) {
      this.gameDatas = gameDatas;

      // Récupération du nom des plateformes de jeu
      const uniquePlatforms = [...new Set(this.gameDatas['datas']['game_prices'].map(data => data['platform']))];

      // Création du menu déroulant des plateformes de jeu
      const platformMenu = document.getElementById('menu-platform');
      platformMenu.innerHTML = '';
      uniquePlatforms.forEach(platform => {
        const menuList = document.createElement('li');
        platformMenu.appendChild(menuList);
        const menuLink = document.createElement('a');
        menuLink.classList.add('dropdown-item');
        menuLink.setAttribute('data-value', secureInput(platform));
        menuLink.textContent = secureInput(platform);
        menuLink.addEventListener('click', e => {
          e.preventDefault();
          this.updateButton(e.target.textContent.trim(), e.target.getAttribute('data-value'));
          new LocationSelect(this.gameDatas, this.getPlatformSelectValue());
        });
        menuList.appendChild(menuLink);
      });

      // Récupération du premier élément de la liste des plateformes de jeu et affichage dans le bouton
      this.platformButton = document.getElementById('platforms');
      const firstItem = document.querySelector('#menu-platform .dropdown-item');
      const firstText = firstItem.textContent.trim();
      const firstValue = firstItem.getAttribute('data-value');
      this.updateButton(firstText, firstValue);

      // Création du sélecteur de localisation
      new LocationSelect(this.gameDatas, this.getPlatformSelectValue());
  }

  updateButton(text, value) {
    this.platformButton.innerHTML = '';
    this.platformButton.textContent = secureInput(text);
    this.platformButton.setAttribute('data-selected-value', value);
  }

  getPlatformSelectValue() {
    return this.platformButton.getAttribute('data-selected-value');
  }

}