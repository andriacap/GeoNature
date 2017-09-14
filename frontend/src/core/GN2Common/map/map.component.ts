import { Component, Input, OnInit, ViewChild } from '@angular/core';
import { MapService } from './map.service';
import {NgbModal, NgbActiveModal} from '@ng-bootstrap/ng-bootstrap';
import {Map} from 'leaflet';
import 'leaflet-draw';
import * as L from 'leaflet';

@Component({
  selector: 'pnx-map',
  templateUrl: './map.component.html',
  styleUrls: ['./map.component.scss']
})
export class MapComponent implements OnInit {
  public map: Map;
  public Le: any;
  searchLocation: string;
  constructor(public mapService: MapService, private modalService: NgbModal) {
    this.searchLocation = '';
    this.Le = L as any;
  }

  ngOnInit() {
    this.mapService.initialize();
    this.map = this.mapService.map;

    // reference the modal content in the map service
    //this.mapService.modalContent = this.modalContent;
  }

    gotoLocation() {
        if (!this.searchLocation) { return; }
        this.mapService.search(this.searchLocation);
    }

}
