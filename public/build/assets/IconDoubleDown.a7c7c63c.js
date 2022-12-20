import{o as s,c as t,F as l,d,s as _,w as h,b as n,a as o,aa as i,n as m,u as p,I as f,f as k,t as u,_ as b}from"./app.188a3521.js";const y={class:"flex items-center justify-center"},g={class:"bg-primary-darker md:bg-white p-2 md:group-hover:bg-primary-darker rounded-full transition-colors duration-200 ease-in"},$=["onClick"],v={class:"bg-primary-darker md:bg-white p-2 md:group-hover:bg-primary-darker rounded-full transition-colors duration-200 ease-in"},F={__name:"ActionColumn",props:{actions:{type:Array,required:!0}},emits:["action-clicked"],setup(r){return(c,x)=>(s(),t("div",y,[(s(!0),t(l,null,d(r.actions,(e,a)=>(s(),t(l,{key:a},[e.as==="link"?(s(),_(p(f),{key:0,href:e.route,class:m(["md:m-1 flex items-center group",{"m-2":r.actions.length>1}])},{default:h(()=>[n("span",g,[o(i,{name:e.icon,theme:e.theme,class:"w-4 h-4"},null,8,["name","theme"])])]),_:2},1032,["href","class"])):e.as==="button"?(s(),t("button",{key:a,class:m(["md:m-2 flex items-center group",{"m-2":r.actions.length>1}]),onClick:w=>c.$emit("action-clicked",e)},[n("span",v,[o(i,{name:e.icon,theme:e.theme,class:"w-4 h-4"},null,8,["name","theme"])])],10,$)):k("",!0)],64))),128))]))}},C={class:"mt-2 py-0 overflow-hidden shadow-xl bg-complement text-white cursor-pointer rounded text-sm whitespace-nowrap"},L={class:"flex items-center space-x-2"},B=["onClick"],A={class:"flex items-center space-x-2"},j={__name:"ActionDropdown",props:{actions:{type:Array,required:!0}},emits:["action-clicked"],setup(r){return(c,x)=>(s(),t("div",C,[(s(!0),t(l,null,d(r.actions,(e,a)=>(s(),t(l,{key:a},[e.as==="link"?(s(),_(p(f),{key:0,href:e.route,class:"block w-full text-left px-4 py-2"},{default:h(()=>[n("span",L,[o(i,{name:e.icon,class:"w-4 h-4"},null,8,["name"]),n("span",null,u(e.label),1)])]),_:2},1032,["href"])):e.as==="button"?(s(),t("button",{key:a,class:"block w-full text-left px-4 py-2",onClick:w=>c.$emit("action-clicked",e)},[n("span",A,[o(i,{name:e.icon,class:"w-4 h-4"},null,8,["name"]),n("span",null,u(e.label),1)])],8,B)):k("",!0)],64))),128))]))}},D={},I={viewBox:"0 0 320 512"},z=n("path",{fill:"currentColor",d:"M143 256.3L7 120.3c-9.4-9.4-9.4-24.6 0-33.9l22.6-22.6c9.4-9.4 24.6-9.4 33.9 0l96.4 96.4 96.4-96.4c9.4-9.4 24.6-9.4 33.9 0L313 86.3c9.4 9.4 9.4 24.6 0 33.9l-136 136c-9.4 9.5-24.6 9.5-34 .1zm34 192l136-136c9.4-9.4 9.4-24.6 0-33.9l-22.6-22.6c-9.4-9.4-24.6-9.4-33.9 0L160 352.1l-96.4-96.4c-9.4-9.4-24.6-9.4-33.9 0L7 278.3c-9.4 9.4-9.4 24.6 0 33.9l136 136c9.4 9.5 24.6 9.5 34 .1z"},null,-1),N=[z];function V(r,c){return s(),t("svg",I,N)}var E=b(D,[["render",V]]);export{E as I,F as _,j as a};
