import{r as y,A as g,j as p,h as n,c as s,t as u,g as c,F as w,m as k,q as m,b as d,G as x,ai as B,u as V}from"./app-dd899f9b.js";const O=["id"],R={key:0,class:"form-label"},S={class:"text-accent flex items-center"},C=["id","value","name","checked","disabled"],q=["for","textContent"],F={key:1,class:"form-error-block mt-0"},D={__name:"FormRadio",props:{modelValue:{type:[String,Number],default:""},options:{type:Array,required:!0},name:{type:String,required:!0},label:{type:String,default:""},disabled:{type:Boolean},error:{type:String,default:""},allowReset:{type:Boolean},allowOther:{type:Boolean},narrow:{type:Boolean}},emits:["update:modelValue","autosave"],setup(e,{expose:b,emit:i}){const r=e,a=y(r.modelValue);g(()=>a.value,l=>{i("update:modelValue",l),i("autosave")});const v=p(()=>{let l=["string","number"].includes(typeof r.options[0])?r.options.map(function(o){return{value:o,label:o}}):[...r.options];return r.allowOther&&l.push({label:"Other",value:"other"}),!r.allowReset||a.value===null||l.push({label:"Remove",value:null}),l});return b({setOther:l=>{a.value=l}}),(l,o)=>(n(),s("div",{id:e.name??null,class:"form-scroll-mt"},[e.label?(n(),s("label",R,u(e.label)+" :",1)):c("",!0),(n(!0),s(w,null,k(V(v),(t,f)=>(n(),s("div",{key:f,class:m(["mb-2 flex text-gray-600 appearance-none w-full py-1 px-2 lg:py-2 lg:px-3 bg-white shadow-sm rounded border-2 transition-colors duration-200 ease-in-out cursor-pointer",{"border-red-400":e.error,"border-gray-200":!a.value&&!e.error,"opacity-50":a.value!==null&&a.value!==t.value,"border-accent font-normal":a.value===t.value&&!e.error,"bg-gray-200":e.disabled}])},[d("div",S,[x(d("input",{id:t.value+"-"+e.name,type:"radio",class:"shadow-sm h-5 w-5 transition-all duration-200 ease-in-out appearance-none inline-block align-middle rounded-full border border-complement-darker select-none shrink-0 cursor-pointer focus:outline-none",value:t.value,name:e.name,"onUpdate:modelValue":o[0]||(o[0]=h=>a.value=h),checked:t.value===a.value,disabled:e.disabled},null,8,C),[[B,a.value]])]),d("label",{for:t.value+"-"+e.name,textContent:u(t.label),class:m(["w-full block cursor-pointer",{"ml-1":e.narrow,"ml-4":!e.narrow,"text-gray-400":e.disabled}])},null,10,q)],2))),128)),e.error?(n(),s("p",F,u(e.error),1)):c("",!0)],8,O))}};export{D as _};
