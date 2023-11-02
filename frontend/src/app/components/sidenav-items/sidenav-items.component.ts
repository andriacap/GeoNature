import { Component, OnInit } from '@angular/core';
import { ConfigService } from '@geonature/services/config.service';
import { ModuleService } from '../../services/module.service';
import { SideNavService } from './sidenav-service';

@Component({
  selector: 'pnx-sidenav-items',
  templateUrl: './sidenav-items.component.html',
  styleUrls: ['./sidenav-items.component.scss'],
})
export class SidenavItemsComponent implements OnInit {
  public nav = [{}];
  public version = null;
  public home_page: any;
  public exportModule: any;
  public isOpenedSideNav: boolean;

  constructor(
    public moduleService: ModuleService,
    public _sidenavService: SideNavService,
    public config: ConfigService,
  ) {
    this.version = this.config.GEONATURE_VERSION;
  }

  ngOnInit() {
    this.home_page = this._sidenavService.getHomeItem();
    this._sidenavService.currentSideNavStatus$.subscribe((isOpened) => {
      this.isOpenedSideNav = isOpened;
    });
  }

  setHome() {
    this.moduleService.currentModule$.next(null);
  }
}
