import{o as l,c as o,b as t,l as d,t as a,j as u}from"./app.02e41e9a.js";const h={key:0},b=["id"],g={class:"relative"},k={class:"ml-3 text-sm md:text-base xl:text-lg"},x={key:0,class:"form-error-block"},f={key:1},y=["id"],V=["checked"],v={class:"ml-4 text-sm md:text-base xl:text-lg"},w={key:0,class:"form-error-block"},N={__name:"FormCheckbox",props:{modelValue:{type:Boolean},name:{type:String,default:""},label:{type:String,default:""},error:{type:String,default:""},toggler:{type:Boolean}},emits:["update:modelValue","autosave"],setup(e,{expose:m,emit:n}){const s=e,r=()=>{n("update:modelValue",!s.modelValue),n("autosave")};return m({check:()=>{n("update:modelValue",!s.modelValue)}}),(C,S)=>{var c,i;return e.toggler?(l(),o("div",h,[t("label",{class:"inline-flex items-center cursor-pointer form-scroll-mt",id:(c=e.name)!=null?c:null},[t("div",g,[t("input",{type:"checkbox",class:"hidden",onChange:r},null,32),t("div",{class:d(["w-8 h-5 rounded-full shadow-inner transition-all duration-200 ease-in-out",{"bg-accent-darker":e.modelValue,"bg-gray-200":!e.modelValue}])},null,2),t("div",{class:d(["absolute w-5 h-5 bg-white rounded-full shadow inset-y-0 left-0 transition-all duration-200 ease-in-out transform",{"translate-x-3":e.modelValue}])},null,2)]),t("div",k,a(e.label),1)],8,b),e.error?(l(),o("p",x,a(e.error),1)):u("",!0)])):(l(),o("div",f,[t("label",{class:"inline-flex items-center cursor-pointer form-scroll-mt",id:(i=e.name)!=null?i:null},[t("input",{type:"checkbox",class:"shadow-xs h-6 w-6 transition-all duration-200 ease-in-out appearance-none color inline-block align-middle border border-gray-400 select-none shrink-0 rounded cursor-pointer focus:outline-none",checked:e.modelValue,onChange:r},null,40,V),t("span",v,a(e.label),1)],8,y),e.error?(l(),o("p",w,a(e.error),1)):u("",!0)]))}}};export{N as _};