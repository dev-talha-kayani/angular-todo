import { Component, OnInit } from '@angular/core';
import { Hero } from './hero';
import { HeroService } from './hero.service';
import {CdkDragDrop, moveItemInArray, transferArrayItem} from '@angular/cdk/drag-drop';
@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent implements OnInit {
  heros: Hero[] = [];
  error = '';
  success = '';
  public inputvalue: string = '';
  hero: Hero = <Hero>{};
  text = '';
  constructor(private service: HeroService) { }
  ngOnInit() {
    this.getList();
  }
  UpdateColor(obj,event){
    let color = obj.color;
    switch (color){
      case 'colorGreen':
        obj.color = 'colorRed';
        break;
      case 'colorRed':
        obj.color = 'colorBlue';
        break;
      case 'colorBlue':
        obj.color = 'colorGreen';
        break;

    }
    console.log(obj);
    this.service.updatecolor(obj).subscribe(
      (res: Hero[]) => {
      },
      (err) => this.error = err
    );

  }
  updatetext(obj,text){
    
    var text = text.target.value;
    obj.text = text;
    this.service.updatetextinput(obj).subscribe(
      (res: Hero[]) => {
       
      },
      (err) => this.error = err
    );

  }
  delete(obj,index){
    
      var id = obj.id;
      this.service.deleterow(id).subscribe(
        (res: Hero[]) => {
          this.heros.splice(index,1);
        },
        (err) => this.error = err
      );
  }
  checkStatus(obj){
    return Number(obj.status) == 1
  }
  checkclass(){
    return Number(status) == 1
  }
  marked(obj){
    let id = obj.id;
    if(obj.status == 1){
      obj.status = 0;
    }else{
      obj.status = 1;
    }
    this.service.mark(obj).subscribe(
      (res: Hero[]) => {
      },
      (err) => this.error = err
    );
  }
  getList(): void {
    this.service.getAll().subscribe(
      (res: Hero[]) => {
        this.heros = res;
      },
      (err) => {
        this.error = err;
      }
    );
  }
  addtodo(f) {
    this.service.store(this.hero).subscribe(
        (res: any) => {
         this.heros.push(res);
         f.reset();
        },
        (err) => this.error = err
      );
  }
  drop(event: CdkDragDrop<string[]>) {
    moveItemInArray(this.heros, event.previousIndex, event.currentIndex);
    let list: number[] = [] ;
    this.heros.forEach(x => {
      list.push(x.id);
    });
    this.service.sort(list).subscribe(
      (res: Hero[]) => {
       
      },
        (err) => this.error = err
      );
    }
}