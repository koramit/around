import{r as h,C as y,q as g,o as n,c as s,t as u,k as m,F as k,v as w,l as x,b as d,M as V,N as B,u as C}from"./app.afd383f4.js";const O=["id"],R={key:0,class:"form-label"},S={class:"text-accent flex items-center"},N=["id","value","name","checked"],q=["for","textContent"],F={key:1,class:"form-error-block mt-0"},z={__name:"FormRadio",props:{modelValue:{type:[String,Number],default:""},options:{type:Array,required:!0},name:{type:String,required:!0},label:{type:String,default:""},disabled:{type:Boolean},error:{type:String,default:""},allowReset:{type:Boolean},allowOther:{type:Boolean}},emits:["update:modelValue","autosave"],setup(e,{expose:v,emit:i}){const o=e,l=h(o.modelValue);y(()=>l.value,a=>{i("update:modelValue",a),i("autosave")});const b=g(()=>{let a=["string","number"].includes(typeof o.options[0])?o.options.map(function(r){return{value:r,label:r}}):[...o.options];return o.allowOther&&a.push({label:"Other",value:"other"}),!o.allowReset||l.value===null||a.push({label:"Remove",value:null}),a});return v({setOther:a=>{l.value=a}}),(a,r)=>{var c;return n(),s("div",{id:(c=e.name)!=null?c:null,class:"form-scroll-mt"},[e.label?(n(),s("label",R,u(e.label)+" :",1)):m("",!0),(n(!0),s(k,null,w(C(b),(t,p)=>(n(),s("div",{key:p,class:x(["mb-2 flex text-gray-600 appearance-none w-full py-1 px-2 lg:py-2 lg:px-3 bg-white shadow-sm rounded border-2 transition-colors duration-200 ease-in-out cursor-pointer",{"border-red-400":e.error,"border-gray-200":!l.value&&!e.error,"opacity-50":l.value&&l.value!==t.value,"border-accent font-normal":l.value===t.value&&!e.error}])},[d("div",S,[V(d("input",{id:t.value+"-"+e.name,type:"radio",class:"shadow-sm h-5 w-5 transition-all duration-200 ease-in-out appearance-none inline-block align-middle rounded-full border border-complement-darker select-none shrink-0 cursor-pointer focus:outline-none",value:t.value,name:e.name,"onUpdate:modelValue":r[0]||(r[0]=f=>l.value=f),checked:t.value===l.value},null,8,N),[[B,l.value]])]),d("label",{for:t.value+"-"+e.name,textContent:u(t.label),class:"ml-4 w-full block cursor-pointer"},null,8,q)],2))),128)),e.error?(n(),s("p",F,u(e.error),1)):m("",!0)],8,O)}}};export{z as _};