import{r as p,x as c,i as w,h as n,c as s,t as u,g as m,F as x,l as k,p as b,b as d,J as V,aj as B}from"./app-Cw0y1MbD.js";const O=["id"],R={key:0,class:"form-label"},S={class:"text-accent flex items-center"},C=["id","value","name","checked","disabled"],F=["for","textContent"],N={key:1,class:"form-error-block mt-0"},j={__name:"FormRadio",props:{modelValue:{type:[String,Number],default:""},options:{type:Array,required:!0},name:{type:String,required:!0},label:{type:String,default:""},disabled:{type:Boolean},error:{type:String,default:""},allowReset:{type:Boolean},allowOther:{type:Boolean},narrow:{type:Boolean}},emits:["update:modelValue","autosave"],setup(e,{expose:v,emit:h}){const i=h,t=e,l=p(t.modelValue);c(()=>l.value,a=>{i("update:modelValue",a),i("autosave")}),c(()=>t.modelValue,a=>{l.value=a});const y=w(()=>{let a=["string","number"].includes(typeof t.options[0])?t.options.map(function(r){return{value:r,label:r}}):[...t.options];return t.allowOther&&a.push({label:"Other",value:"other"}),!t.allowReset||l.value===null||a.push({label:"Remove",value:null}),a});return v({setOther:a=>{l.value=a}}),(a,r)=>(n(),s("div",{id:e.name??null,class:"form-scroll-mt"},[e.label?(n(),s("label",R,u(e.label)+" :",1)):m("",!0),(n(!0),s(x,null,k(y.value,(o,f)=>(n(),s("div",{key:f,class:b(["mb-2 flex text-gray-600 appearance-none w-full py-1 px-2 lg:py-2 lg:px-3 bg-white shadow-sm rounded border-2 transition-colors duration-200 ease-in-out cursor-pointer",{"border-red-400":e.error,"border-gray-200":!l.value&&!e.error,"opacity-50":l.value!==null&&l.value!==o.value,"border-accent font-normal":l.value===o.value&&!e.error,"bg-gray-200":e.disabled}])},[d("div",S,[V(d("input",{id:o.value+"-"+e.name,type:"radio",class:"shadow-sm h-5 w-5 transition-all duration-200 ease-in-out appearance-none inline-block align-middle rounded-full border border-complement-darker select-none shrink-0 cursor-pointer focus:outline-none",value:o.value,name:e.name,"onUpdate:modelValue":r[0]||(r[0]=g=>l.value=g),checked:o.value===l.value,disabled:e.disabled},null,8,C),[[B,l.value]])]),d("label",{for:o.value+"-"+e.name,textContent:u(o.label),class:b(["w-full block cursor-pointer",{"ml-1":e.narrow,"ml-4":!e.narrow,"text-gray-400":e.disabled}])},null,10,F)],2))),128)),e.error?(n(),s("p",N,u(e.error),1)):m("",!0)],8,O))}};export{j as _};
