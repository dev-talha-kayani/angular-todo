import { Injectable } from '@angular/core';
import { HttpClient, HttpErrorResponse, HttpParams } from '@angular/common/http';
import { Observable, throwError } from 'rxjs';
import { map, catchError } from 'rxjs/operators';
import { Hero } from './hero';

@Injectable({
    providedIn: 'root'
})
export class HeroService {
    baseUrl = 'http://localhost:8080';
    hero: Hero[];
    constructor(private http: HttpClient) { }
    getAll(): Observable<Hero[]> {
        return this.http.get(this.baseUrl+'/list')
          .pipe(map((res) => {
                this.hero = res['data'];
                return this.hero;
            }));
    }
    store(hero: Hero): Observable<Hero[]> {
      return this.http.post(this.baseUrl+'/insert', { data: hero })
        .pipe(map((res) => {
          this.hero.push(res['data']);
          return this.hero;
      }));
  }
    updatecolor(hero: Hero): Observable<Hero[]> {
      return this.http.post(this.baseUrl+'/colorupdtae', { data: hero })
        .pipe(map((res) => {
          this.hero = res['data'];
          return this.hero;
      }));
    }
    deleterow(hero: Hero): Observable<Hero[]> {
      return this.http.post(this.baseUrl+'/delete', { data: hero })
        .pipe(map((res) => {
          this.hero = res['data'];
          return this.hero;
      }));
    }
    mark(hero: Hero): Observable<Hero[]> {
      return this.http.post(this.baseUrl+'/markread', { data: hero })
        .pipe(map((res) => {
          this.hero = res['data'];
          return this.hero;
      }));
    }
    updatetextinput(hero: Hero): Observable<Hero[]> {
      return this.http.post(this.baseUrl+'/updatetext', { data: hero })
        .pipe(map((res) => {
          this.hero = res['data'];
          return this.hero;
      }));
    }
    sort(hero: number[]): Observable<Hero[]> {
      return this.http.post(this.baseUrl+'/updatepositions', { data: hero })
        .pipe(map((res) => {
          this.hero = res['data'];
          return this.hero;
        }));
    }

}
