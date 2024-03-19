import { Component, OnInit, ViewChild, ViewEncapsulation } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { FormControl, FormGroup, FormBuilder, Validators, ValidatorFn } from '@angular/forms';
import { Observable, of } from 'rxjs';
import { forkJoin } from 'rxjs/observable/forkJoin';
import { concatMap, flatMap, skip, finalize } from 'rxjs/operators';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';

import { CommonService } from '@geonature_common/service/common.service';
import { CruvedStoreService } from '@geonature_common/service/cruved-store.service';

import { ImportDataService } from '../../../services/data.service';
import { FieldMappingService } from '../../../services/mappings/field-mapping.service';
import { Import, EntitiesThemesFields } from '../../../models/import.model';
import { ImportProcessService } from '../import-process.service';
import { ContentMapping, FieldMapping, FieldMappingValues } from '../../../models/mapping.model';
import { Step } from '../../../models/enums.model';
import { ConfigService } from '@geonature/services/config.service';
import {
  Cruved,
  CruvedWithScope,
  toBooleanCruved,
} from '@geonature/modules/imports/models/cruved.model';

@Component({
  selector: 'fields-mapping-step',
  styleUrls: ['fields-mapping-step.component.scss'],
  templateUrl: 'fields-mapping-step.component.html',
  encapsulation: ViewEncapsulation.None,
})
export class FieldsMappingStepComponent implements OnInit {
  public step: Step;
  public importData: Import; // the current import
  public spinner: boolean = false;
  public userFieldMappings: Array<FieldMapping>; // all field mapping accessible by the users

  public targetFields: Array<EntitiesThemesFields>; // list of target fields, i.e. fields, ordered by theme, grouped by entities
  public mappedTargetFields: Set<string>;
  public unmappedTargetFields: Set<string>;

  public sourceFields: Array<string>; // list of all source fields of the import
  public autogeneratedFields: Array<string> = [];
  public mappedSourceFields: Set<string>;
  public unmappedSourceFields: Set<string>;
  public autoMappedFields: Array<string> = [];

  public formReady: boolean = false; // do not show frontend fields until all forms are ready
  public fieldMappingForm = new FormControl(); // form to select the mapping to use
  public mappingFormControl: FormGroup; // form group to associate each source fields to import fields
  public createOrRenameMappingForm = new FormControl(null, [Validators.required]); // form to add a new mapping
  public modalCreateMappingForm = new FormControl('');

  public displayAllValues: boolean = false; // checkbox to (not) show fields associated by the selected mapping
  public createMappingFormVisible: boolean = false; // show mapping creation form
  public renameMappingFormVisible: boolean = false; // show rename mapping form
  public updateAvailable: boolean = false;
  public mappingSelected: boolean = false;

  private cruved: Cruved;

  @ViewChild('saveMappingModal') saveMappingModal;
  @ViewChild('deleteConfirmModal') deleteConfirmModal;

  public selectedIndex: number = null;

  constructor(
    private _importDataService: ImportDataService,
    private _fm: FieldMappingService,
    private _commonService: CommonService,
    private _fb: FormBuilder,
    private importProcessService: ImportProcessService,
    private _route: ActivatedRoute,
    private _modalService: NgbModal,
    public cruvedStore: CruvedStoreService,
    public config: ConfigService
  ) {
    this.displayAllValues = this.config.IMPORT.DISPLAY_MAPPED_VALUES;
  }

  ngOnInit() {
    this.step = this._route.snapshot.data.step;
    this.importData = this.importProcessService.getImportData();

    this.cruved = toBooleanCruved(this.cruvedStore.cruved.IMPORT.module_objects.MAPPING.cruved);

    forkJoin({
      fieldMappings: this._importDataService.getFieldMappings(),
      targetFields: this._importDataService.getBibFields(),
      sourceFields: this._importDataService.getColumnsImport(this.importData.id_import),
    }).subscribe(({ fieldMappings, targetFields, sourceFields }) => {
      this.userFieldMappings = fieldMappings;

      this.targetFields = targetFields;
      this.mappedTargetFields = new Set();
      this.unmappedTargetFields = new Set();
      this.mappingFormControl = this._fb.group({});
      this.populateMappingForm();
      this.mappingFormControl.setValidators([this._fm.geoFormValidator]);
      this.mappingFormControl.updateValueAndValidity();

      this.sourceFields = sourceFields;

      this.targetFields.forEach((entity) => {
        entity.themes.forEach((theme) => {
          theme.fields.forEach(({ autogenerated, name_field }) => {
            if (autogenerated) {
              this.autogeneratedFields.push(name_field);
            }
          });
        });
      });

      this.mappedSourceFields = new Set();
      this.unmappedSourceFields = new Set(sourceFields);
      if (this.importData.fieldmapping) {
        this.fillFormWithMapping(this.importData.fieldmapping);
      }

      // subscribe to changes of selected field mapping
      this.fieldMappingForm.valueChanges
        .pipe(
          // skip first empty value to avoid reseting the field form if importData as mapping:
          skip(this.importData.fieldmapping === null ? 0 : 1)
        )
        .subscribe((mapping) => {
          this.onNewMappingSelected(mapping);
        });

      this.formReady = true;
    });
  }

  // Used by select component to compare field mappings
  areMappingFieldEqual(fm1: FieldMapping, fm2: FieldMapping): boolean {
    return fm1 != null && fm2 != null && fm1.id === fm2.id;
  }

  // add a form control for each target field in the mappingForm
  // mandatory target fields have a required validator
  displayAlert(field) {
    return (
      field.name_field === 'unique_id_sinp_generate' &&
      !this.mappingFormControl.get(field.name_field).value
    );
  }

  /**
   * Count the number of invalid controls
   * in an entity FormGroup
   */
  invalidEntityControls(entityFormLabel: string) {
    let result: number = 0;
    this.targetFields
      .find(({ entity }) => entity.label === entityFormLabel)
      .themes.forEach(({ fields }) => {
        fields.forEach((field) => {
          let control = this.mappingFormControl.controls[field.name_field];
          result += control.status === 'INVALID' ? 1 : 0;
        });
      });
    return result;
  }

  /**
   * A function to populate the mapping form based on target fields.
   */
  populateMappingForm() {
    this.targetFields.forEach((entity) => {
      entity.themes.forEach(({ fields }) => {
        fields.forEach((field) => {
          const { name_field, autogenerated } = field;
          if (!(name_field in this.mappingFormControl.controls)) {
            if (!autogenerated) {
              this.unmappedTargetFields.add(name_field);
            }
            const validators: Array<ValidatorFn> = field.mandatory ? [Validators.required] : [];
            const control = new FormControl(null, validators);
            control.valueChanges.subscribe((value) => {
              this.onFieldMappingChange(name_field, value);
            });
            this.mappingFormControl.addControl(name_field, control);
          } else {
            // If a control with a given name already exists, it would not be replaced with a new one.
            // The existing one will be updated with a new obser. We make sure to sync the references of it.
            this.mappingFormControl.controls[name_field].valueChanges.subscribe((value) => {
              this.mappingFormControl.controls[name_field].setValue(value, {
                onlySelf: true,
                emitEvent: false,
                emitModelToViewChange: true,
              });
            });
            this.mappingFormControl.addControl(
              name_field,
              this.mappingFormControl.controls[name_field]
            );
          }
        });
      });
    });
  }

  // should activate the "rename mapping" button or gray it?
  renameMappingEnabled() {
    // a mapping have been selected and we have update right on it
    return this.fieldMappingForm.value != null && this.fieldMappingForm.value.cruved.U;
  }

  deleteMappingEnabled() {
    // a mapping have been selected and we have delete right on it
    return this.fieldMappingForm.value != null && this.fieldMappingForm.value.cruved.D;
  }

  showRenameMappingForm() {
    this.createOrRenameMappingForm.setValue(this.fieldMappingForm.value.label);
    this.createMappingFormVisible = false;
    this.renameMappingFormVisible = true;
  }

  hideCreateOrRenameMappingForm() {
    this.createMappingFormVisible = false;
    this.renameMappingFormVisible = false;
    this.createOrRenameMappingForm.reset();
  }
  getValue(field) {
    return this.autoMappedFields.includes(field.name_field);
  }
  createMapping() {
    this.spinner = true;
    this._importDataService
      .createFieldMapping(this.modalCreateMappingForm.value, this.getFieldMappingValues())
      .pipe()
      .subscribe(
        () => {
          this.processNextStep();
        },
        () => {
          this.spinner = false;
        }
      );
  }
  updateMapping() {
    this.spinner = true;
    let name = '';
    if (this.modalCreateMappingForm.value != this.fieldMappingForm.value.label) {
      name = this.modalCreateMappingForm.value;
    }
    this._importDataService
      .updateFieldMapping(this.fieldMappingForm.value.id, this.getFieldMappingValues(), name)
      .pipe()
      .subscribe(
        () => {
          this.processNextStep();
        },
        () => {
          this.spinner = false;
        }
      );
  }
  openDeleteModal() {
    this._modalService.open(this.deleteConfirmModal);
  }
  deleteMapping() {
    this.spinner = true;
    let mapping_id = this.fieldMappingForm.value.id;
    this._importDataService
      .deleteFieldMapping(mapping_id)
      .pipe()
      .subscribe(
        () => {
          this._commonService.regularToaster(
            'success',
            'Le mapping ' + this.fieldMappingForm.value.label + ' a bien été supprimé'
          );
          this.fieldMappingForm.setValue(null, { emitEvent: false });
          this.userFieldMappings = this.userFieldMappings.filter((mapping) => {
            return mapping.id !== mapping_id;
          });
          this.spinner = false;
        },
        () => {
          this.spinner = false;
        }
      );
  }

  renameMapping(): void {
    this.spinner = true;
    this._importDataService
      .renameFieldMapping(this.fieldMappingForm.value.id, this.createOrRenameMappingForm.value)
      .pipe(
        finalize(() => {
          this.spinner = false;
          this.spinner = false;
          this.renameMappingFormVisible = false;
        })
      )
      .subscribe((mapping: FieldMapping) => {
        let index = this.userFieldMappings.findIndex((m: FieldMapping) => m.id == mapping.id);
        this.fieldMappingForm.setValue(mapping);
        this.userFieldMappings[index] = mapping;
      });
  }
  /**
   * Callback when a new mapping is selected
   *
   * @param {FieldMapping} mapping - the selected mapping
   */
  onNewMappingSelected(mapping: FieldMapping = null): void {
    this.hideCreateOrRenameMappingForm();
    if (mapping === null) {
      this.mappingFormControl.reset();
      for (const field of this.autogeneratedFields) {
        const control = this.mappingFormControl.get(field);
        if (
          field !== 'unique_id_sinp_generate' ||
          this.config.IMPORT.DEFAULT_GENERATE_MISSING_UUID
        ) {
          control.setValue(true);
        }
      }
      this.mappingSelected = false;
    } else {
      this.fillFormWithMapping(mapping.values, true);
      this.mappingSelected = true;
    }
  }

  /**
   * Fill the field form with the value define in the given mapping
   * @param mapping : id of the mapping
   */
  fillFormWithMapping(mappingvalues: FieldMappingValues, fromMapping = false) {
    // Retrieve fields for this mapping
    this.mappingFormControl.reset();
    this.autoMappedFields = [];
    for (const [target, source] of Object.entries(mappingvalues)) {
      let control = this.mappingFormControl.get(target);
      let value;
      if (!control) continue; // masked field?
      if (typeof source === 'object') {
        control.setValue(source);
        value = source.filter((x) => this.sourceFields.includes(x));
        if (value.length === 0) continue;
        control.setValue(value);
      } else {
        if (!this.sourceFields.includes(source) && !this.autogeneratedFields.includes(target))
          continue; // this field mapping does not apply to this file and is not autogenerated
        control.setValue(source);
      }
      if (fromMapping) {
        this.autoMappedFields.push(target);
      }
    }
  }

  // a new source field have been selected for a given target field
  onFieldMappingChange(field: string, value: string) {
    if (value == null) {
      if (this.mappedTargetFields.has(field)) {
        this.mappedTargetFields.delete(field);
        this.unmappedTargetFields.add(field);
      }
    } else {
      if (this.unmappedTargetFields.has(field)) {
        this.unmappedTargetFields.delete(field);
        this.mappedTargetFields.add(field);
      }
    }

    this.mappedSourceFields.clear();
    this.unmappedSourceFields = new Set(this.sourceFields);
    for (let entity of this.targetFields) {
      for (let theme of entity.themes) {
        for (let targetField of theme.fields) {
          let sourceField = this.mappingFormControl.get(targetField.name_field).value;
          if (sourceField != null) {
            if (Array.isArray(sourceField)) {
              sourceField.forEach((sf) => {
                this.unmappedSourceFields.delete(sf);
                this.mappedSourceFields.add(sf);
              });
            } else {
              this.unmappedSourceFields.delete(sourceField);
              this.mappedSourceFields.add(sourceField);
            }
          }
        }
      }
    }
  }

  onPreviousStep() {
    this.importProcessService.navigateToPreviousStep(this.step);
  }

  isNextStepAvailable() {
    return this.mappingFormControl && this.mappingFormControl.valid;
  }

  onNextStep() {
    if (!this.isNextStepAvailable()) {
      return;
    }
    let mappingValue = this.fieldMappingForm.value;
    if (
      this.mappingFormControl.dirty &&
      (this.cruved.C || (mappingValue && mappingValue.cruved.U && !mappingValue.public))
    ) {
      if (mappingValue && !mappingValue.public) {
        this.updateAvailable = true;
        this.modalCreateMappingForm.setValue(mappingValue.label);
      } else {
        this.updateAvailable = false;
        this.modalCreateMappingForm.setValue('');
      }
      this._modalService.open(this.saveMappingModal, { size: 'lg' });
    } else {
      this.spinner = true;
      this.processNextStep();
    }
  }
  onSaveData(loadImport = false): Observable<Import> {
    return of(this.importData).pipe(
      concatMap((importData: Import) => {
        if (this.mappingSelected || this.mappingFormControl.dirty) {
          return this._importDataService.setImportFieldMapping(
            importData.id_import,
            this.getFieldMappingValues()
          );
        } else {
          return of(importData);
        }
      }),
      concatMap((importData: Import) => {
        if (!importData.loaded && loadImport) {
          return this._importDataService.loadImport(importData.id_import);
        } else {
          return of(importData);
        }
      }),
      concatMap((importData: Import) => {
        if (
          (this.mappingSelected || this.mappingFormControl.dirty) &&
          !this.config.IMPORT.ALLOW_VALUE_MAPPING
        ) {
          return this._importDataService
            .getContentMapping(this.config.IMPORT.DEFAULT_VALUE_MAPPING_ID)
            .pipe(
              flatMap((mapping: ContentMapping) => {
                return this._importDataService.setImportContentMapping(
                  importData.id_import,
                  mapping.values
                );
              })
            );
        } else {
          return of(importData);
        }
      }),
      finalize(() => (this.spinner = false))
    );
  }

  processNextStep() {
    this.onSaveData(true).subscribe((importData: Import) => {
      this.importProcessService.setImportData(importData);
      this.importProcessService.navigateToNextStep(this.step);
    });
  }

  getFieldMappingValues(): FieldMappingValues {
    let values: FieldMappingValues = {};
    for (let [key, value] of Object.entries(this.mappingFormControl.value)) {
      if (value != null) {
        values[key] = Array.isArray(value) ? value : (value as string);
      }
    }
    return values;
  }
}
