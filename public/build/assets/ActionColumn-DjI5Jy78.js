import{h as s,c as t,F as n,l as d,j as u,e as h,b as o,d as c,V as i,p as m,u as p,z as g,g as k}from"./app-CsDEoyKE.js";const f={class:"flex items-center justify-center"},_={class:"bg-primary-darker md:bg-white p-2 md:group-hover:bg-primary-darker rounded-full transition-colors duration-200 ease-in"},b=["onClick"],y={class:"bg-primary-darker md:bg-white p-2 md:group-hover:bg-primary-darker rounded-full transition-colors duration-200 ease-in"},v={__name:"ActionColumn",props:{actions:{type:Array,required:!0}},emits:["action-clicked"],setup(r){return(l,C)=>(s(),t("div",f,[(s(!0),t(n,null,d(r.actions,(e,a)=>(s(),t(n,{key:a},[e.as==="link"?(s(),u(p(g),{key:0,href:e.route,class:m(["md:m-1 flex items-center group",{"m-2":r.actions.length>1}])},{default:h(()=>[o("span",_,[c(i,{name:e.icon,theme:e.theme,class:"w-4 h-4"},null,8,["name","theme"])])]),_:2},1032,["href","class"])):e.as==="button"?(s(),t("button",{key:a,class:m(["md:m-2 flex items-center group",{"m-2":r.actions.length>1}]),onClick:w=>l.$emit("action-clicked",e)},[o("span",y,[c(i,{name:e.icon,theme:e.theme,class:"w-4 h-4"},null,8,["name","theme"])])],10,b)):k("",!0)],64))),128))]))}};export{v as _};
