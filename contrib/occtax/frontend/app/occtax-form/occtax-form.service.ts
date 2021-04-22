import { Injectable } from "@angular/core";
import { FormControl } from "@angular/forms";
import { BehaviorSubject } from "rxjs";
import { filter, tap, skip } from "rxjs/operators";

import { AppConfig } from "@geonature_config/app.config";
import { HttpClient, HttpParams } from "@angular/common/http";
import { Router } from "@angular/router";
import { AuthService, User } from "@geonature/components/auth/auth.service";
import { CommonService } from "@geonature_common/service/common.service";
import { OcctaxDataService } from "../services/occtax-data.service";


@Injectable()
export class OcctaxFormService {
  //id du relevé récupérer en GET
  public id_releve_occtax: BehaviorSubject<number> = new BehaviorSubject(null);
  //si ID en get récupération des données du relevé
  public occtaxData: BehaviorSubject<any> = new BehaviorSubject(null);

  public currentUser: User;
  public disabled = true;
  public editionMode: BehaviorSubject<boolean> = new BehaviorSubject(false); // boolean to check if its editionMode
  public chainRecording: boolean = false; // boolean to check if chain the recording is activate
  public stayOnFormInterface = new FormControl(false);
  public currentIdDataset:any;
  public previousReleve = null;
  public dataSetConfig: any;
  public globalReleveAddFields: Array<any> = [];
  public globalOccurrenceAddFields: Array<any>= [];
  public globalCountingAddFields: Array<any>= [];
  public datasetReleveAddFields: Array<any>= [];
  public datasetOccurrenceAddFields: Array<any>= [];
  public datasetCountingAddFields: Array<any>= [];

  public nomenclatureAdditionnel: any = [];

  constructor(
    private _http: HttpClient,
    private _router: Router,
    private _auth: AuthService,
    private _commonService: CommonService,
    private _dataS: OcctaxDataService,

  ) {    
    this.currentUser = this._auth.getCurrentUser();

    //observation de l'URL
    this.id_releve_occtax
      .pipe(
        skip(1), // skip initilization value (null)
        tap((id) =>{ 
          if(id == null) {
            this.editionMode.next(false)
          }
        }), //reinitialisation du mode edition à faux
        filter((id) => id !== null)
      )
      .subscribe((id) => this.getOcctaxData(id));
  }

  getOcctaxData(id) {
    
    this._dataS.getOneReleve(id).subscribe(
      (data) => {        
        this.occtaxData.next(data);
        this.editionMode.next(true);
      },
      (error) => {
        this._commonService.translateToaster("error", "Releve.DoesNotExist");
        this._router.navigate(["occtax/form"]);
      }
    );
  }

  getDefaultValues(idOrg?: number, regne?: string, group2_inpn?: string) {
    let params = new HttpParams();
    if (idOrg) {
      params = params.set("organism", idOrg.toString());
    }
    if (group2_inpn) {
      params = params.append("regne", regne);
    }
    if (regne) {
      params = params.append("group2_inpn", group2_inpn);
    }
    return this._http.get<any>(
      `${AppConfig.API_ENDPOINT}/occtax/defaultNomenclatures`,
      {
        params: params,
      }
    );
  }

  storeAdditionalNomenclaturesValues(nomenclatures_types: Array<any>) {    
    // store all nomenclatures element in a array in order to find
    // the label on submit    
    nomenclatures_types.forEach(nomenc_type => {
      nomenc_type.values.forEach(nomenc_element => {
        nomenc_element['MNEMONIQUE_TYPE'] = nomenc_type.mnemonique
        this.nomenclatureAdditionnel.push(nomenc_element);
      });
    });    
  }

  onEditReleve(id) {
    this._router.navigate(["occtax/form", id]);
  }
  backToList() {
    this._router.navigate(["occtax"]);
  }

  formDisabled() {
    if (this.disabled) {
      this._commonService.translateToaster(
        "warning",
        "Releve.FillGeometryFirst"
      );
    }
  }

  addOccurrenceData(occurrence): void {
    let occtaxData = this.occtaxData.getValue();    

    if (!occtaxData.releve.properties.t_occurrences_occtax) {
      occtaxData.releve.properties.t_occurrences_occtax = [];
    }
    occtaxData.releve.properties.t_occurrences_occtax.push(occurrence);
    
    this.occtaxData.next(occtaxData);
  }

  removeOccurrenceData(id_occurrence): void {
    let occtaxData = this.occtaxData.getValue();
    if (occtaxData.releve.properties.t_occurrences_occtax) {
      for (
        let i = 0;
        i < occtaxData.releve.properties.t_occurrences_occtax.length;
        i++
      ) {
        if (
          occtaxData.releve.properties.t_occurrences_occtax[i]
            .id_occurrence_occtax === id_occurrence
        ) {
          occtaxData.releve.properties.t_occurrences_occtax.splice(i, 1);
          break;
        }
      }
    }
    this.occtaxData.next(occtaxData);
  }

  replaceOccurrenceData(occurrence): void {
    this.removeOccurrenceData(occurrence.id_occurrence_occtax);
    this.addOccurrenceData(occurrence);
  }

  replaceReleveData(releve): void {
    let occtaxData = this.occtaxData.getValue();
    occtaxData.releve = releve;
    this.occtaxData.next(occtaxData);
  }


  formatDate(strDate) {
    const date = new Date(strDate);
    return {
      year: date.getFullYear(),
      month: date.getMonth() + 1,
      day: date.getDate(),
    };
  }

  clearFormerAdditonnalFields(globalAddFields, formerDatasetAddFields) {
      // copy object in order to not modify him directly (he is linked to an input)
      let inter = Object.assign([], globalAddFields);
      // remove formde dataset additional field from globalAddfield
      inter = globalAddFields.filter(globalField => {
        return !formerDatasetAddFields.map(f => f.id_field).includes(globalField.id_field)
      })

      return inter;
  }

}
