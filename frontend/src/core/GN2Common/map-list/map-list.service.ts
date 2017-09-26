import { Injectable} from '@angular/core';
import { Subject } from 'rxjs/Subject';
import { Http } from '@angular/http';
import { AppConfig } from '../../../conf/app.config';
import { Observable } from 'rxjs';
import * as L from 'leaflet';
@Injectable()
export class MapListService {
  private _layerId = new Subject<any>();
  private _tableId = new Subject<any>();
  public columns = [];
  public layerDict= {};
  public selectedLayer: any;
  public gettingLayerId$: Observable<number> = this._layerId.asObservable();
  public gettingTableId$: Observable<number> = this._tableId.asObservable();
  originStyle = {
    'color': '#3388ff',
    'fill': true,
    'fillOpacity': 0.2,
    'weight': 3
  };

 selectedStyle = {
  'color': '#ff0000',
   'weight': 3
  };
    constructor(private _http: Http) {
      this.columns = [];
  }

  getData(endPoint, param?) {
    console.log(param);
    const params: URLSearchParams = new URLSearchParams();
     param  ? params.append(param.param, param.value) : params.set('', '');
    return this._http.get(`${AppConfig.API_ENDPOINT}${endPoint}`, {search: params.toString()})
      .map(res => res.json());
  }

  setCurrentLayerId(id: number) {
    this._layerId.next(id);
  }

  setCurrentTableId(id: number) {
    this._tableId.next(id);
  }

  toggleStyle(selectedLayer) {
    // togle the style of selected layer
    if ( this.selectedLayer !== undefined) {
      this.selectedLayer.setStyle(this.originStyle);
      this.selectedLayer.closePopup();
    }
    this.selectedLayer = selectedLayer;
    this.selectedLayer.setStyle(this.selectedStyle);
  }

  zoomOnSelectedLayer(map, layer) {
    const zoom = map.getZoom();
    // latlng is different between polygons and point
    let latlng;

    if(layer instanceof L.Polygon || layer instanceof L.Polyline){
      latlng = (layer as any)._bounds._northEast;
    }else {
      latlng = layer._latlng;
    }
    if (zoom >= 12) {
      map.setView(latlng, zoom);
    } else {
      map.setView(latlng, 12);
  }
  }

  loadTableData(data) {
    const tableData = [];
    data.features.forEach(feature => {
      const obj = feature.properties;
      obj['id'] = feature.id;
      tableData.push(obj);
    });
    return tableData;
  }
}
