import{_,b as r,c as i,d as t,r as p,G as b,H as y,s as v,aj as a,e as L,h as x}from"./app-CC1FPugz.js";const E={},M={viewBox:"0 0 352 512"},N=t("path",{fill:"currentColor",d:"M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z"},null,-1),k=[N];function $(s,d){return r(),i("svg",M,k)}const D=_(E,[["render",$]]),V={class:"flex justify-between items-center"},B={__name:"ModalDialog",props:{widthMode:{type:String,default:"document"}},emits:["opened","closed"],setup(s,{expose:d,emit:h}){const l=h,o=p(!1),n=p(!1),w=e=>{requestAnimationFrame(()=>{requestAnimationFrame(e)})},c=e=>{e.target.tagName=="DIV"&&e.propertyName=="transform"&&(l("opened"),document.removeEventListener("transitionend",c))},u=e=>{e.target.tagName==="DIV"&&e.propertyName==="transform"&&(l("closed"),o.value=!1,document.removeEventListener("transitionend",u))},g=()=>{document.addEventListener("transitionend",c),o.value=!0,w(()=>{n.value=!0})},m=()=>{document.addEventListener("transitionend",u),n.value=!1};return d({open:g,close:m}),(e,f)=>b((r(),i("div",{class:v(["inset-0 z-30 fixed flex items-center justify-center backdrop",{open:n.value}])},[o.value?(r(),i("div",{key:0,class:v(["bg-primary rounded shadow p-4 md:p-8 xl:p-10 modal-appear-from-top",{open:n.value,"w-11/12 md:10/12":s.widthMode=="document","w-11/12 sm:10/12 md:w-3/5 xl:w-2/5":s.widthMode=="form-cols-1"}])},[t("div",V,[t("div",null,[a(e.$slots,"header",{},void 0,!0)]),t("button",{onClick:f[0]||(f[0]=C=>m()),class:"block p-2 rounded-full hover:bg-white bg-primary transition-colors ease-in-out duration-200"},[L(D,{class:"w-4 h-4"})])]),t("div",null,[a(e.$slots,"body",{},void 0,!0)]),t("div",null,[a(e.$slots,"footer",{},void 0,!0)])],2)):x("",!0)],2)),[[y,o.value]])}},j=_(B,[["__scopeId","data-v-a3217df5"]]);export{j as M};