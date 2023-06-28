import{r as k,j as g,z as p,b as l,c as o,t as d,h as c,d as t,F as u,m as h,s as f,n as V}from"./app-4c4070f2.js";const C={class:"w-full"},O=["for"],B={key:1,class:"relative"},L=["id","name","placeholder","disabled","value"],S=t("option",{disabled:"",value:""}," Please select ",-1),_=["disabled"],z=["value"],j=["value"],F={key:0,value:"other"},N=t("div",{class:"pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700"},[t("svg",{class:"fill-current h-4 w-4",xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 20 20"},[t("path",{d:"M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"})])],-1),R={key:2,class:"flex"},q={class:"relative w-full"},M=["id","name","placeholder","disabled","value"],P=t("option",{disabled:"",value:""}," Please select ",-1),A=["disabled"],D=["value"],E=["value"],T={key:0,value:"other"},G=t("div",{class:"pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700"},[t("svg",{class:"fill-current h-4 w-4",xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 20 20"},[t("path",{d:"M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"})])],-1),H={class:"w-auto flex items-center px-2 border-2 border-gray-200 rounded shadow-sm border-l-0 rounded-l-none bg-gray-50"},I={class:"inline-flex items-center"},J=["checked"],K={class:"ml-4 text-lg cursor-pointer whitespace-nowrap"},Q={key:3,class:"form-error-block"},Y={__name:"FormSelect",props:{modelValue:{type:[String,Number,null],default:""},modelCheckbox:{type:Boolean},options:{type:Array,required:!0},name:{type:String,required:!0},label:{type:String,default:""},placeholder:{type:String,default:""},disabled:{type:Boolean},error:{type:String,default:""},allowOther:{type:Boolean},switchLabel:{type:String,default:""}},emits:["autosave","update:modelValue","update:modelCheckbox"],setup(e,{expose:w,emit:n}){const i=e,m=k(null),b=g(()=>typeof i.options[0]=="object"?[]:i.options),v=g(()=>typeof i.options[0]=="object"?i.options:[]);p(()=>i.modelValue,s=>{s==="Remove"&&(n("update:modelValue",null),n("autosave"))});const y=s=>{n("update:modelValue",s.target.value),n("autosave")},x=s=>{n("update:modelCheckbox",s.target.checked)};return w({setOther:s=>{V(()=>{m.value.value=s,n("update:modelValue",s),n("autosave")})}}),(s,W)=>(l(),o("div",C,[e.label?(l(),o("label",{key:0,class:"form-label",for:e.name},d(e.label)+" :",9,O)):c("",!0),e.switchLabel?(l(),o("div",R,[t("div",q,[t("select",{id:e.name,name:e.name,ref_key:"input",ref:m,placeholder:e.placeholder,disabled:e.disabled,value:e.modelValue,onChange:y,class:f(["form-input cursor-pointer disabled:cursor-not-allowed border-r-1 rounded-r-none form-scroll-mt",{"!border-red-400 !text-red-400":e.error,"bg-gray-400":e.disabled}])},[P,t("option",{class:"italic text-yellow-500",disabled:!e.modelValue}," Remove ",8,A),(l(!0),o(u,null,h(v.value,(a,r)=>(l(),o("option",{key:r,value:a.value},d(a.label),9,D))),128)),(l(!0),o(u,null,h(b.value,(a,r)=>(l(),o("option",{key:r,value:a},d(a),9,E))),128)),e.allowOther?(l(),o("option",T," Other ")):c("",!0)],42,M),G]),t("div",H,[t("label",I,[t("input",{type:"checkbox",class:"shadow-xs h-6 w-6 transition-all duration-200 ease-in-out appearance-none color inline-block align-middle border border-gray-400 select-none shrink-0 rounded cursor-pointer focus:outline-none",checked:e.modelCheckbox,onChange:x},null,40,J),t("span",K,d(e.switchLabel),1)])])])):(l(),o("div",B,[t("select",{id:e.name,name:e.name,ref_key:"input",ref:m,placeholder:e.placeholder,disabled:e.disabled,value:e.modelValue,onChange:y,class:f(["form-input cursor-pointer disabled:cursor-not-allowed border-r-1 form-scroll-mt",{"!border-red-400 !text-red-400":e.error,"bg-gray-400":e.disabled}])},[S,t("option",{class:"italic text-yellow-500",disabled:!e.modelValue}," Remove ",8,_),(l(!0),o(u,null,h(v.value,(a,r)=>(l(),o("option",{key:r,value:a.value},d(a.label),9,z))),128)),(l(!0),o(u,null,h(b.value,(a,r)=>(l(),o("option",{key:r,value:a},d(a),9,j))),128)),e.allowOther?(l(),o("option",F," Other ")):c("",!0)],42,L),N])),e.error?(l(),o("div",Q,d(e.error),1)):c("",!0)]))}};export{Y as _};