import { Component, OnInit, Input} from '@angular/core';
import { MapService } from '../map/map.service';
import {MapListService} from '../map-list/map-list.service';
import { GeoJSON, Layer } from 'leaflet';


@Component({
  selector: 'pnx-map-list',
  templateUrl: './map-list.component.html',
  styleUrls: ['./map-list.component.scss'],
  providers: [MapService]
})
export class MapListComponent implements OnInit {
  public layerDict: any;
  public selectedLayer: any;
  @Input() geojsonData: GeoJSON;
  @Input() tableData = [];
  @Input() apiEndPoint: string;
  @Input() columns: Array<string>;
  @Input() pathRedirect: string;

  constructor(private _ms: MapService, private _mapListService: MapListService) {
  }

  ngOnInit() {
    // event from the list
    this._mapListService.gettingLayerId$.subscribe(res => {
      const selectedLayer = this._mapListService.layerDict[res];
      this._mapListService.toggleStyle(selectedLayer);
      this._mapListService.zoomOnSelectedLayer(this._ms.map, selectedLayer);
    });
  }

  refreshValue(params) {
    console.log(params);
    this._mapListService.getData('contact/vrelevecontact', params)
      .subscribe(res => {
        this.geojsonData = res;
        this.tableData = this._mapListService.loadTableData(res);
      });

  }

  onEachFeature(feature, layer) {
    // event from the map
    this._mapListService.layerDict[feature.id] = layer;
    layer.on({
      click : (e) => {
        // toggle style
        this._mapListService.toggleStyle(layer);
        // observable
        this._mapListService.setCurrentTableId(feature.id);
        // open popup
        layer.bindPopup(feature.properties.leaflet_popup).openPopup();
      }
    });
  }




}
