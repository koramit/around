import{_ as m,o as s,c,a as e,r as p,E as f,k as _,b as v,w as i,t as d,F as x,v as g,H as k,i as w,y}from"./app.94c97313.js";import{t as b,p as C}from"./throttle.5ae9b617.js";const I={},L={viewBox:"0 0 448 512"},$=e("path",{fill:"currentColor",d:"M224.3 273l-136 136c-9.4 9.4-24.6 9.4-33.9 0l-22.6-22.6c-9.4-9.4-9.4-24.6 0-33.9l96.4-96.4-96.4-96.4c-9.4-9.4-9.4-24.6 0-33.9L54.3 103c9.4-9.4 24.6-9.4 33.9 0l136 136c9.5 9.4 9.5 24.6.1 34zm192-34l-136-136c-9.4-9.4-24.6-9.4-33.9 0l-22.6 22.6c-9.4 9.4-9.4 24.6 0 33.9l96.4 96.4-96.4 96.4c-9.4 9.4-9.4 24.6 0 33.9l22.6 22.6c9.4 9.4 24.6 9.4 33.9 0l136-136c9.4-9.2 9.4-24.4 0-33.8z"},null,-1),B=[$];function z(t,a){return s(),c("svg",L,B)}var G=m(I,[["render",z]]);const S={class:"flex items-center w-full md:w-auto"},j=["value"],D={key:0,class:"flex justify-end form-input md:w-auto !border-l-0 !rounded-l-none"},N={class:"flex items-center cursor-pointer select-none group"},V=e("div",null,"Scope : ",-1),q={class:"group-hover:text-accent-darker focus:text-accent-darker mr-1 whitespace-no-wrap"},E={class:"text-complement group-hover:text-accent-darker focus:text-accent-darker"},F=e("div",{class:"p-1 rounded-full bg-white hover:bg-primary transition-colors ease-in-out duration-200"},[e("svg",{class:"w-3 h-3 text-accent group-hover:text-accent-darker focus:text-accent-darker",viewBox:"0 0 320 512"},[e("path",{fill:"currentColor",d:"M143 256.3L7 120.3c-9.4-9.4-9.4-24.6 0-33.9l22.6-22.6c9.4-9.4 24.6-9.4 33.9 0l96.4 96.4 96.4-96.4c9.4-9.4 24.6-9.4 33.9 0L313 86.3c9.4 9.4 9.4 24.6 0 33.9l-136 136c-9.4 9.5-24.6 9.5-34 .1zm34 192l136-136c9.4-9.4 9.4-24.6 0-33.9l-22.6-22.6c-9.4-9.4-24.6-9.4-33.9 0L160 352.1l-96.4-96.4c-9.4-9.4-24.6-9.4-33.9 0L7 278.3c-9.4 9.4-9.4 24.6 0 33.9l136 136c9.4 9.5 24.6 9.5 34 .1z"})])],-1),M={class:"mt-2 py-2 shadow-xl bg-white text-complement cursor-pointer rounded text-sm"},O=["onClick"],J={__name:"SearchIndex",props:{scopes:{type:Array,default:()=>[]},form:{type:Object,required:!0}},emits:["searchChanged","scopeChanged"],setup(t,{expose:a}){const u=t,l=p(null);return f(()=>u.form,b(function(n){let o=C(n);o=Object.keys(o).length?o:{remember:"forget"},y.Inertia.get(location.pathname,o,{preserveState:!0})},800),{deep:!0}),a({focus:()=>l.value.focus()}),(n,o)=>(s(),c("div",S,[e("input",{class:_(["form-input md:w-auto",{"!border-r-0 !rounded-r-none":t.scopes.length}]),type:"text",name:"search",onInput:o[0]||(o[0]=r=>n.$emit("searchChanged",r.target.value)),value:t.form.search,placeholder:"search...",autocomplete:"off",ref_key:"searchInput",ref:l},null,42,j),t.scopes.length?(s(),c("div",D,[v(k,null,{default:i(()=>[e("div",N,[V,e("div",q,[e("span",E,d(t.form.scope),1)]),F])]),dropdown:i(()=>[e("div",M,[(s(!0),c(x,null,g(t.scopes.filter(r=>r!==t.form.scope),(r,h)=>(s(),c("button",{class:"block w-full text-left font-semibold px-6 py-2 transition-colors duration-200 ease-out hover:bg-primary hover:text-accent-darker",key:h,onClick:H=>n.$emit("scopeChanged",r)},d(r),9,O))),128))])]),_:1})])):w("",!0)]))}};export{G as I,J as _};
