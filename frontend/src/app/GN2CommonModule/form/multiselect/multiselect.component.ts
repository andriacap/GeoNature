import {
  Component,
  OnInit,
  Input,
  EventEmitter,
  Output,
  OnChanges,
  ElementRef,
  ViewChild,
  HostListener,
  ViewChildren,
  QueryList,
} from '@angular/core';
import { FormControl } from '@angular/forms';
import { TranslateService } from '@ngx-translate/core';

export enum KEY_CODE {
  ENTER = 'Enter',
  ARROW_DOWN = 'ArrowDown',
  ARROW_UP = 'ArrowUp',
}

/**
 * Ce composant permet d'afficher un input de type multiselect à partir
 * d'une liste de valeurs passé en Input.
 *
 * @example
 * <pnx-multiselect
 *   label="Organisme"
 *   [values]="organisms"
 *   [parentFormControl]="form.controls.organisms"
 *   keyLabel="nom_organisme"
 *   keyValue="id_organisme"
 *   (onChange)="doWhatever($event)"
 *   (onDelete)="deleteCallback($event)"
 *   (onSearch)="filterItems($event)"
 * >
 * </pnx-multiselect>
 */
@Component({
  selector: 'pnx-multiselect',
  templateUrl: './multiselect.component.html',
  styleUrls: ['./multiselect.component.scss']
})
export class MultiSelectComponent implements OnInit {
  public selectedItems = [];
  public searchControl = new FormControl();
  public formControlValue = [];

  @Input() parentFormControl: FormControl;
  /** Valeurs à afficher dans la liste déroulante. Doit être un tableau
   * de dictionnaire.
   */
  @Input() values: Array<any>;
  /** Clé du dictionnaire de valeur que le composant doit prendre pour
   * l'affichage de la liste déroulante.
   */
  @Input() keyLabel: string;
  /** Clé du dictionnaire que le composant doit passer au formControl */
  @Input() keyValue: string;
  /** Est-ce que le composant doit afficher l'item "tous" dans les
   * options du select ?
   */
  @Input() displayAll: boolean;
  /** Affiche la barre de recherche (=true). */
  @Input() searchBar: boolean;
 /** Désactive le contrôle de formulaire. */
  @Input() disabled: boolean;
  /** Initutlé du contrôle de formulaire. */
  @Input() label: any;
  /**
   * Booléan qui permet de passer tout l'objet au formControl, et pas
   * seulement une propriété de l'objet renvoyé par l'API.
   *
   * Facultatif, par défaut à ``false``, c'est alors l'attribut passé en
   * Input ``keyValue`` qui est renvoyé au formControl.
   * Lorsque l'on passe ``true`` à cet Input, l'Input ``keyValue``
   * devient inutile.
   * L'API qui renvoit séléctionnées au formulaire doit être un tableau
   * d'entier et non un tableau d'items
   */
  @Input() bindAllItem: boolean = false;
  /** Temps en millisecondes avant lancement d'une recherche avec les
   * caractères saisis dans la zone de recherche.
   *
   * Par défaut, 100 millisecondes.
   */
  @Input() debounceTime: number;
  /** Indique (=true) que le contenu de la liste doit être conscidéré
   * comme du HTML sûr.
   */
  @Input() isHtml: boolean = false;

  /** @ignore */
  constructor(private _translate: TranslateService) {}

  // you can pass whatever callback to the onSearch output, to trigger
  // database research or simple search on an array
  ngOnInit() {
    this.debounceTime = this.debounceTime || 100;
    this.disabled = this.disabled || false;
    this.searchBar = this.searchBar || false;
    this.displayAll = this.displayAll || false;

  }

}
