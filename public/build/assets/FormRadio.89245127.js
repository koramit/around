import{r as y,C as w,m as g,o as n,c as s,t as u,i as m,F as k,v as x,k as v,a as d,a2 as B,a3 as V,u as C}from"./app.a2148d35.js";const O=["id"],R={key:0,class:"form-label"},S={class:"text-accent flex items-center"},F=["id","value","name","checked"],N=["for","textContent"],q={key:1,class:"form-error-block mt-0"},A={__name:"FormRadio",props:{modelValue:{type:[String,Number],default:""},options:{type:Array,required:!0},name:{type:String,required:!0},label:{type:String,default:""},disabled:{type:Boolean},error:{type:String,default:""},allowReset:{type:Boolean},allowOther:{type:Boolean},narrow:{type:Boolean}},emits:["update:modelValue","autosave"],setup(e,{expose:b,emit:i}){const r=e,a=y(r.modelValue);w(()=>a.value,l=>{i("update:modelValue",l),i("autosave")});const f=g(()=>{let l=["string","number"].includes(typeof r.options[0])?r.options.map(function(o){return{value:o,label:o}}):[...r.options];return r.allowOther&&l.push({label:"Other",value:"other"}),!r.allowReset||a.value===null||l.push({label:"Remove",value:null}),l});return b({setOther:l=>{a.value=l}}),(l,o)=>{var c;return n(),s("div",{id:(c=e.name)!=null?c:null,class:"form-scroll-mt"},[e.label?(n(),s("label",R,u(e.label)+" :",1)):m("",!0),(n(!0),s(k,null,x(C(f),(t,h)=>(n(),s("div",{key:h,class:v(["mb-2 flex text-gray-600 appearance-none w-full py-1 px-2 lg:py-2 lg:px-3 bg-white shadow-sm rounded border-2 transition-colors duration-200 ease-in-out cursor-pointer",{"border-red-400":e.error,"border-gray-200":!a.value&&!e.error,"opacity-50":a.value&&a.value!==t.value,"border-accent font-normal":a.value===t.value&&!e.error}])},[d("div",S,[B(d("input",{id:t.value+"-"+e.name,type:"radio",class:"shadow-sm h-5 w-5 transition-all duration-200 ease-in-out appearance-none inline-block align-middle rounded-full border border-complement-darker select-none shrink-0 cursor-pointer focus:outline-none",value:t.value,name:e.name,"onUpdate:modelValue":o[0]||(o[0]=p=>a.value=p),checked:t.value===a.value},null,8,F),[[V,a.value]])]),d("label",{for:t.value+"-"+e.name,textContent:u(t.label),class:v(["w-full block cursor-pointer",{"ml-1":e.narrow,"ml-4":!e.narrow}])},null,10,N)],2))),128)),e.error?(n(),s("p",q,u(e.error),1)):m("",!0)],8,O)}}};export{A as _};
