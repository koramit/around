import{I as v}from"./CaseEdit-Cts3FbSD.js";import{_ as $}from"./FormDatetime-BpjhpCMX.js";import{_ as y}from"./FormCheckbox-C2kLr4xg.js";import{_ as U}from"./FormInput-CXQbVQFs.js";import{_ as B,I as x}from"./ImageUploader-mvlc8UH_.js";import{r as C,v as F,h as i,c as d,F as p,l as c,b as l,d as n,t as _,j as I,n as w}from"./app-CsDEoyKE.js";import"./FormAutocomplete-7s3Zyzv7.js";import"./throttle-CadurjLG.js";import"./FormSelect-DYB6bB_Z.js";import"./FormRadio-CRMiEUOy.js";import"./useSelectOther-Dd8eM4G3.js";import"./FormTextarea-BIIXHr8N.js";import"./IconPaperclip-BNDVNhqd.js";import"./SpinnerButton-DJMJUfXj.js";import"./useConfirmForm-BAPzGjGR.js";import"./IconEyesSlash-Bfl2jZwN.js";import"./IconEyes-Dhn5ONCE.js";const j={class:"grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8"},k={class:"space-y-2 md:space-y-4"},q={class:"form-label"},N=["id"],T={class:"mt-2 md:mt-4 grid grid-cols-2 gap-2 md:gap-4"},A=["onClick"],Z={__name:"GraftBiopsyForm",props:{modelValue:{type:Array,required:!0},configs:{type:Object,required:!0}},emits:["update:modelValue","autosave"],setup(m,{emit:f}){const b=m,u=f,s=C([...b.modelValue]);F(()=>s.value,r=>{u("update:modelValue",[...r]),u("autosave")},{deep:!0});function g(r){let o=[...s.value];o.splice(r,1),s.value=[],w(()=>{s.value=o})}return(r,o)=>(i(),d("div",null,[(i(!0),d(p,null,c(s.value,(t,e)=>(i(),d("div",{key:e,class:"my-2 md:my-4 space-y-2 md:space-y-4"},[l("div",j,[l("div",k,[n($,{label:`date of biopsy#${e+1}`,name:`graft_biopsies.${e}.date_biopsy`,modelValue:t.date_biopsy,"onUpdate:modelValue":a=>t.date_biopsy=a,error:r.$page.props.errors["graft_biopsies."+e+".date_biopsy"]},null,8,["label","name","modelValue","onUpdate:modelValue","error"]),l("div",null,[l("label",q,"result biopsy#"+_(e+1)+" :",1),l("small",{class:"form-error-block form-scroll-mt",id:`graft_biopsies.${e}.result`},_(r.$page.props.errors[`graft_biopsies.${e}.result`]),9,N),l("div",T,[(i(!0),d(p,null,c(m.configs.biopsy_result_fields,(a,h)=>(i(),I(y,{key:h,label:a.label,name:`graft_biopsies.${e}.result.${a.name}`,modelValue:t.result[a.name],"onUpdate:modelValue":V=>t.result[a.name]=V},null,8,["label","name","modelValue","onUpdate:modelValue"]))),128))]),n(U,{class:"mt-2 md:mt-4",name:`graft_biopsies.${e}.result.other_result`,modelValue:t.result.other_result,"onUpdate:modelValue":a=>t.result.other_result=a,placeholder:"other result"},null,8,["name","modelValue","onUpdate:modelValue"])])]),n(B,{label:`attachment biopsy#${e+1}`,"service-endpoints":m.configs.routes.upload,pathname:m.configs.attachment_upload_pathname,modelValue:t.attachment,"onUpdate:modelValue":a=>t.attachment=a},null,8,["label","service-endpoints","pathname","modelValue","onUpdate:modelValue"]),l("button",{class:"block",onClick:a=>g(e)},[n(x,{class:"w-4 h-4 text-red-400"})],8,A)]),o[1]||(o[1]=l("hr",{class:"border border-dashed my-2 md:my-4 xl:my-8"},null,-1))]))),128)),l("button",{onClick:o[0]||(o[0]=t=>s.value.push({...m.configs.graft_biopsy}))},[n(v,{class:"w-4 h-4 text-accent"})])]))}};export{Z as default};
