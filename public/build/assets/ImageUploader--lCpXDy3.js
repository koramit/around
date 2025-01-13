import{_ as w,h as a,c as n,b as s,r as v,t as x,j as p,g as u,d as f,e as V,m as $,I as C}from"./app-DsPwriMT.js";import{I as b}from"./IconEyesSlash-CHuLMHUc.js";import{I as E}from"./IconEyes-sm5tYBYd.js";import{I as z}from"./IconTrashXMark-DqCI6dyc.js";const B={},M={viewBox:"0 0 512 512"};function S(t,o){return a(),n("svg",M,o[0]||(o[0]=[s("path",{fill:"currentColor",d:"M512 144v288c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V144c0-26.5 21.5-48 48-48h88l12.3-32.9c7-18.7 24.9-31.1 44.9-31.1h125.5c20 0 37.9 12.4 44.9 31.1L376 96h88c26.5 0 48 21.5 48 48zM376 288c0-66.2-53.8-120-120-120s-120 53.8-120 120 53.8 120 120 120 120-53.8 120-120zm-32 0c0 48.5-39.5 88-88 88s-88-39.5-88-88 39.5-88 88-88 88 39.5 88 88z"},null,-1)]))}const L=w(B,[["render",S]]),N={},_={viewBox:"0 0 512 512"};function q(t,o){return a(),n("svg",_,o[0]||(o[0]=[s("path",{fill:"currentColor",d:"M464 64H48C21.49 64 0 85.49 0 112v288c0 26.51 21.49 48 48 48h416c26.51 0 48-21.49 48-48V112c0-26.51-21.49-48-48-48zm-6 336H54a6 6 0 0 1-6-6V118a6 6 0 0 1 6-6h404a6 6 0 0 1 6 6v276a6 6 0 0 1-6 6zM128 152c-22.091 0-40 17.909-40 40s17.909 40 40 40 40-17.909 40-40-17.909-40-40-40zM96 352h320v-80l-87.515-87.515c-4.686-4.686-12.284-4.686-16.971 0L192 304l-39.515-39.515c-4.686-4.686-12.284-4.686-16.971 0L96 304v48z"},null,-1)]))}const H=w(N,[["render",q]]),T={class:"flex items-center"},j={class:"form-label !mb-0 !inline"},D={key:2},G={key:0,class:"text-red-700 text-sm"},F=["src"],J={__name:"ImageUploader",props:{modelValue:{type:String,default:""},label:{type:String,required:!0},pathname:{type:String,required:!0},error:{type:String,default:""},readonly:{type:Boolean},serviceEndpoints:{type:Object,required:!0}},emits:["update:modelValue","autosave","removed"],setup(t,{emit:o}){const d=o,r=t,y=v(null),k=v(null),i=v(!1),c=v(!1),h=v(r.modelValue),g=m=>{c.value=!0;let e=new FormData;e.append("file",m.target.files[0]),e.append("pathname",r.pathname),e.append("old",r.modelValue),window.axios.post(r.serviceEndpoints.store,e).then(l=>{h.value=l.data.filename,d("update:modelValue",l.data.filename),d("autosave"),i.value||(i.value=!0)}).catch(l=>{console.log(l)}).finally(()=>{c.value=!1})};function I(){c.value=!0,i.value=!1,window.axios.delete(r.serviceEndpoints.destroy,{data:{path:r.pathname+"/"+r.modelValue}}).then(function(){h.value="",d("update:modelValue",""),d("autosave"),d("removed")}).catch(m=>{console.log(m)}).finally(()=>{c.value=!1})}return(m,e)=>(a(),n("div",null,[s("div",T,[s("p",null,[s("span",j,x(t.label),1),c.value?(a(),p(C,{key:0,class:"ml-1 w-4 h-4 inline-block opacity-25 animate-spin"})):u("",!0)]),t.readonly?u("",!0):(a(),n("button",{key:0,class:"ml-4",onClick:e[0]||(e[0]=l=>y.value.click())},[f(L,{class:"w-4 h-4 text-thick-theme-light"})])),t.readonly?u("",!0):(a(),n("button",{key:1,class:"ml-4",onClick:e[1]||(e[1]=l=>k.value.click())},[f(H,{class:"w-4 h-4 text-thick-theme-light"})])),t.modelValue?(a(),n("span",D,[s("button",{class:"ml-4",onClick:e[2]||(e[2]=l=>i.value=!i.value)},[i.value?(a(),p(b,{key:0,class:"w-4 h-4 text-dark-theme-light"})):(a(),p(E,{key:1,class:"w-4 h-4 text-dark-theme-light"}))]),t.serviceEndpoints.destroy!==void 0?(a(),n("button",{key:0,class:"ml-4",onClick:I},[f(z,{class:"w-4 h-4 text-red-400"})])):u("",!0)])):u("",!0)]),t.error?(a(),n("div",G,x(t.error),1)):u("",!0),f($,{name:"slide-fade"},{default:V(()=>[t.modelValue!==void 0&&i.value?(a(),n("img",{key:0,src:`${t.serviceEndpoints.show}?path=${t.pathname}/${h.value}`,onLoadstart:e[3]||(e[3]=l=>c.value=!0),onLoad:e[4]||(e[4]=l=>m.$nextTick(()=>c.value=!1)),alt:""},null,40,F)):u("",!0)]),_:1}),s("input",{class:"hidden",type:"file",ref_key:"useCamera",ref:y,onInput:g,capture:"environment",accept:"image/*"},null,544),s("input",{class:"hidden",type:"file",ref_key:"useGallery",ref:k,onInput:g,accept:"image/*"},null,544)]))}};export{J as _};