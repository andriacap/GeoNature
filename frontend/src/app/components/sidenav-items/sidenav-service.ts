import { Injectable } from '@angular/core';
import { MatSidenav } from '@angular/material/sidenav';
import { BehaviorSubject, Subject } from 'rxjs';

@Injectable()
export class SideNavService {
  sidenav: MatSidenav;
  opened: boolean;
  private _module = new Subject<any>();
  public currentModule: any;
  gettingCurrentModule = this._module.asObservable();
  // List of the apps
  public modules: Array<any>;
  public home_page;
  public exportModule;
  opened$ = new BehaviorSubject<boolean>(true);
  currentSideNavStatus$ = this.opened$.asObservable();

  constructor() {
    this.opened = true;
  }

  setSideNav(sidenav: MatSidenav) {
    this.sidenav = sidenav;
  }

  setHome(sidenav: MatSidenav) {
    sidenav.open();
  }

  getCurrentApp() {
    return this.currentModule;
  }

  getHomeItem() {
    return { module_url: '/', module_label: 'Accueil', module_picto: 'fa-home', id: '1' };
  }

  toggleSideNav() {
    this.sidenav.toggle();
    this.opened == true ? this.changeStatusSideNav(false) : this.changeStatusSideNav(true);
  }

  changeStatusSideNav(isOpened: boolean) {
    this.opened = isOpened;
    this.opened$.next(isOpened);
  }
}
